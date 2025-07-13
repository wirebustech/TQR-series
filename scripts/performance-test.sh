#!/bin/bash

# TQRS Performance Testing Suite
# This script runs comprehensive performance tests on the TQRS platform

set -e

# Configuration
BASE_URL="http://localhost:8000"
API_URL="$BASE_URL/api"
RESULTS_DIR="./performance-results"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
REPORT_FILE="$RESULTS_DIR/performance_report_$TIMESTAMP.txt"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Test configuration
LIGHT_LOAD=10
MEDIUM_LOAD=50
HEAVY_LOAD=100
CONCURRENCY_LEVELS=(1 5 10 20)

# Create results directory
mkdir -p "$RESULTS_DIR"

# Logging function
log() {
    echo -e "${BLUE}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$REPORT_FILE"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1" | tee -a "$REPORT_FILE"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$REPORT_FILE"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$REPORT_FILE"
}

# Check if required tools are installed
check_dependencies() {
    log "Checking dependencies..."
    
    commands=("ab" "curl" "jq" "php")
    for cmd in "${commands[@]}"; do
        if ! command -v "$cmd" &> /dev/null; then
            log_error "$cmd is not installed. Please install it to continue."
            exit 1
        fi
    done
    
    log_success "All dependencies are installed"
}

# Health check
health_check() {
    log "Performing health check..."
    
    response=$(curl -s -o /dev/null -w "%{http_code}" "$API_URL/health")
    if [ "$response" -eq 200 ]; then
        log_success "API is healthy and responding"
    else
        log_error "API health check failed (HTTP $response)"
        exit 1
    fi
}

# Get authentication token
get_auth_token() {
    log "Obtaining authentication token..."
    
    # Try to create a test user and get token
    AUTH_TOKEN=$(curl -s -X POST "$API_URL/login" \
        -H "Content-Type: application/json" \
        -d '{"email":"test@example.com","password":"password123"}' | \
        jq -r '.access_token // empty')
    
    if [ -z "$AUTH_TOKEN" ] || [ "$AUTH_TOKEN" == "null" ]; then
        log_warning "Could not obtain auth token. Some tests will be skipped."
        AUTH_TOKEN=""
    else
        log_success "Authentication token obtained"
    fi
}

# Generic performance test function
run_load_test() {
    local endpoint="$1"
    local requests="$2"
    local concurrency="$3"
    local name="$4"
    local auth_header="$5"
    
    log "Testing $name - $requests requests with $concurrency concurrent users"
    
    local ab_cmd="ab -n $requests -c $concurrency -g $RESULTS_DIR/${name}_${requests}_${concurrency}.tsv"
    
    if [ -n "$auth_header" ]; then
        ab_cmd="$ab_cmd -H '$auth_header'"
    fi
    
    ab_cmd="$ab_cmd '$endpoint'"
    
    # Run the test and capture results
    local result_file="$RESULTS_DIR/${name}_${requests}_${concurrency}.txt"
    eval "$ab_cmd" > "$result_file" 2>&1
    
    # Parse results
    local requests_per_second=$(grep "Requests per second" "$result_file" | grep -o '[0-9.]*' | head -1)
    local time_per_request=$(grep "Time per request" "$result_file" | grep -o '[0-9.]*' | head -1)
    local failed_requests=$(grep "Failed requests" "$result_file" | grep -o '[0-9]*' | head -1)
    
    log "  → RPS: $requests_per_second, Time/Request: ${time_per_request}ms, Failed: $failed_requests"
    
    # Check for performance issues
    if (( $(echo "$requests_per_second < 10" | bc -l) )); then
        log_warning "  → Low RPS detected ($requests_per_second < 10)"
    fi
    
    if (( $(echo "$time_per_request > 1000" | bc -l) )); then
        log_warning "  → High response time detected (${time_per_request}ms > 1000ms)"
    fi
    
    if [ "$failed_requests" -gt 0 ]; then
        log_error "  → $failed_requests failed requests detected"
    fi
}

# Test public endpoints
test_public_endpoints() {
    log "=== Testing Public Endpoints ==="
    
    local endpoints=(
        "$API_URL/health:Health Check"
        "$API_URL/articles:Articles List"
        "$API_URL/articles/1:Article Details"
        "$API_URL/webinars:Webinars List"
        "$API_URL/webinars/1:Webinar Details"
        "$API_URL/opportunities:Opportunities List"
        "$API_URL/pages/published:Published Pages"
    )
    
    for endpoint_info in "${endpoints[@]}"; do
        IFS=":" read -r endpoint name <<< "$endpoint_info"
        for load in "${CONCURRENCY_LEVELS[@]}"; do
            run_load_test "$endpoint" "$LIGHT_LOAD" "$load" "public_${name// /_}" ""
        done
    done
}

# Test authentication endpoints
test_auth_endpoints() {
    log "=== Testing Authentication Endpoints ==="
    
    # Test login endpoint
    local login_data='{"email":"test@example.com","password":"password123"}'
    local login_file="$RESULTS_DIR/login_data.json"
    echo "$login_data" > "$login_file"
    
    for load in "${CONCURRENCY_LEVELS[@]}"; do
        log "Testing Login - $LIGHT_LOAD requests with $load concurrent users"
        ab -n "$LIGHT_LOAD" -c "$load" -p "$login_file" -T "application/json" \
           -g "$RESULTS_DIR/auth_login_${LIGHT_LOAD}_${load}.tsv" \
           "$API_URL/login" > "$RESULTS_DIR/auth_login_${LIGHT_LOAD}_${load}.txt" 2>&1
    done
}

# Test authenticated endpoints
test_authenticated_endpoints() {
    if [ -z "$AUTH_TOKEN" ]; then
        log_warning "Skipping authenticated endpoint tests - no auth token"
        return
    fi
    
    log "=== Testing Authenticated Endpoints ==="
    
    local auth_header="Authorization: Bearer $AUTH_TOKEN"
    local endpoints=(
        "$API_URL/user:User Profile"
        "$API_URL/pages:Pages Management"
        "$API_URL/blogs:Blog Management"
        "$API_URL/media-library:Media Library"
        "$API_URL/users:User Management"
        "$API_URL/analytics/overview:Analytics Overview"
    )
    
    for endpoint_info in "${endpoints[@]}"; do
        IFS=":" read -r endpoint name <<< "$endpoint_info"
        for load in "${CONCURRENCY_LEVELS[@]}"; do
            run_load_test "$endpoint" "$LIGHT_LOAD" "$load" "auth_${name// /_}" "$auth_header"
        done
    done
}

# Test database performance
test_database_performance() {
    log "=== Testing Database Performance ==="
    
    if [ -z "$AUTH_TOKEN" ]; then
        log_warning "Skipping database performance tests - no auth token"
        return
    fi
    
    # Test pagination performance
    local endpoints=(
        "$API_URL/articles?per_page=10"
        "$API_URL/articles?per_page=50"
        "$API_URL/articles?per_page=100"
    )
    
    for endpoint in "${endpoints[@]}"; do
        run_load_test "$endpoint" "$MEDIUM_LOAD" "10" "db_pagination" "Authorization: Bearer $AUTH_TOKEN"
    done
    
    # Test search performance
    local search_endpoints=(
        "$API_URL/articles?search=research"
        "$API_URL/pages?search=about"
        "$API_URL/webinars?search=qualitative"
    )
    
    for endpoint in "${search_endpoints[@]}"; do
        run_load_test "$endpoint" "$LIGHT_LOAD" "5" "db_search" "Authorization: Bearer $AUTH_TOKEN"
    done
}

# Test file upload performance
test_file_upload_performance() {
    log "=== Testing File Upload Performance ==="
    
    if [ -z "$AUTH_TOKEN" ]; then
        log_warning "Skipping file upload tests - no auth token"
        return
    fi
    
    # Create a test file
    local test_file="$RESULTS_DIR/test_upload.txt"
    echo "This is a test file for performance testing" > "$test_file"
    
    # Test small file uploads
    for i in {1..10}; do
        local start_time=$(date +%s%3N)
        curl -s -X POST "$API_URL/media-library" \
             -H "Authorization: Bearer $AUTH_TOKEN" \
             -F "file=@$test_file" \
             -F "title=Test Upload $i" > /dev/null
        local end_time=$(date +%s%3N)
        local duration=$((end_time - start_time))
        echo "Upload $i: ${duration}ms" >> "$RESULTS_DIR/upload_performance.txt"
    done
    
    log "File upload performance test completed"
}

# Stress testing
run_stress_tests() {
    log "=== Running Stress Tests ==="
    
    # Stress test critical endpoints
    local critical_endpoints=(
        "$API_URL/health:Health Check"
        "$API_URL/articles:Articles"
        "$API_URL/webinars:Webinars"
    )
    
    for endpoint_info in "${critical_endpoints[@]}"; do
        IFS=":" read -r endpoint name <<< "$endpoint_info"
        log "Stress testing $name with $HEAVY_LOAD requests and 20 concurrent users"
        run_load_test "$endpoint" "$HEAVY_LOAD" "20" "stress_${name// /_}" ""
    done
}

# Performance monitoring
monitor_system_performance() {
    log "=== System Performance Monitoring ==="
    
    # Monitor system resources during tests
    local monitor_file="$RESULTS_DIR/system_monitor_$TIMESTAMP.txt"
    
    {
        echo "=== System Information ==="
        echo "Date: $(date)"
        echo "CPU Info:"
        cat /proc/cpuinfo | grep "model name" | head -1
        echo "Memory Info:"
        free -h
        echo "Disk Usage:"
        df -h
        echo ""
        
        echo "=== Process Information ==="
        ps aux | grep -E "(php|nginx|mysql)" | head -10
        echo ""
        
        echo "=== Network Statistics ==="
        netstat -i
        echo ""
        
    } > "$monitor_file"
    
    log "System performance data saved to $monitor_file"
}

# Generate performance report
generate_report() {
    log "=== Generating Performance Report ==="
    
    local summary_file="$RESULTS_DIR/performance_summary_$TIMESTAMP.txt"
    
    {
        echo "=========================================="
        echo "TQRS Performance Testing Report"
        echo "=========================================="
        echo "Date: $(date)"
        echo "Test Duration: $(date -d @$(($(date +%s) - START_TIME)) -u +%H:%M:%S)"
        echo ""
        
        echo "=== Test Configuration ==="
        echo "Base URL: $BASE_URL"
        echo "Light Load: $LIGHT_LOAD requests"
        echo "Medium Load: $MEDIUM_LOAD requests"
        echo "Heavy Load: $HEAVY_LOAD requests"
        echo "Concurrency Levels: ${CONCURRENCY_LEVELS[*]}"
        echo ""
        
        echo "=== Summary Statistics ==="
        echo "Total Test Files: $(find "$RESULTS_DIR" -name "*.txt" | wc -l)"
        echo "Total TSV Files: $(find "$RESULTS_DIR" -name "*.tsv" | wc -l)"
        echo ""
        
        echo "=== Performance Warnings ==="
        grep -h "WARNING" "$REPORT_FILE" | sort | uniq -c
        echo ""
        
        echo "=== Performance Errors ==="
        grep -h "ERROR" "$REPORT_FILE" | sort | uniq -c
        echo ""
        
        echo "=== Recommendations ==="
        echo "1. Review endpoints with RPS < 10"
        echo "2. Optimize endpoints with response time > 1000ms"
        echo "3. Investigate failed requests"
        echo "4. Consider implementing caching for frequently accessed endpoints"
        echo "5. Monitor database query performance"
        echo "6. Implement rate limiting for public endpoints"
        echo ""
        
        echo "=== Test Files Location ==="
        echo "Results directory: $RESULTS_DIR"
        echo "Detailed logs: $REPORT_FILE"
        echo "System monitor: $RESULTS_DIR/system_monitor_$TIMESTAMP.txt"
        
    } > "$summary_file"
    
    log_success "Performance report generated: $summary_file"
}

# Main execution
main() {
    START_TIME=$(date +%s)
    
    log "Starting TQRS Performance Testing Suite"
    log "Results will be saved to: $RESULTS_DIR"
    
    check_dependencies
    health_check
    get_auth_token
    monitor_system_performance
    
    # Run all tests
    test_public_endpoints
    test_auth_endpoints
    test_authenticated_endpoints
    test_database_performance
    test_file_upload_performance
    run_stress_tests
    
    generate_report
    
    log_success "Performance testing completed successfully!"
    log "Check the summary report: $RESULTS_DIR/performance_summary_$TIMESTAMP.txt"
}

# Run the main function
main "$@" 