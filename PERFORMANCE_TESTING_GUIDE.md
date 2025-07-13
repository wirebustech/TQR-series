# TQRS Performance Testing Guide

This guide explains how to use the comprehensive performance testing suite for The Qualitative Research Series (TQRS) platform.

## Overview

The performance testing suite consists of several components designed to test different aspects of the TQRS platform:

- **API Load Testing**: Tests API endpoints under various load conditions
- **Database Performance Testing**: Analyzes database query performance and optimization
- **WebSocket Performance Testing**: Tests real-time WebSocket connections
- **Performance Monitoring**: Continuous monitoring of application performance
- **Caching and Optimization**: Implements and tests caching strategies

## Prerequisites

Before running performance tests, ensure you have:

1. **Required Tools**:
   - Apache Bench (`ab`) - Install with: `sudo apt-get install apache2-utils`
   - cURL - Usually pre-installed
   - jq - Install with: `sudo apt-get install jq`
   - PHP CLI - For database tests
   - Node.js - For WebSocket tests

2. **Test Environment**:
   - TQRS application running on `http://localhost:8000`
   - Database populated with test data
   - WebSocket server running on `ws://localhost:8080`

3. **Configuration**:
   - Performance monitoring enabled in `backend/config/performance.php`
   - Database query logging enabled
   - Appropriate environment variables set

## Running Performance Tests

### 1. API Load Testing

The main performance test script tests all API endpoints under various load conditions:

```bash
# Make the script executable
chmod +x scripts/performance-test.sh

# Run all performance tests
./scripts/performance-test.sh
```

**Test Configuration:**
- Light Load: 10 requests
- Medium Load: 50 requests
- Heavy Load: 100 requests
- Concurrency Levels: 1, 5, 10, 20 concurrent users

**Endpoints Tested:**
- Public endpoints (health, articles, webinars, opportunities)
- Authentication endpoints (login, register)
- Authenticated endpoints (user profile, admin functions)
- Database-intensive endpoints (pagination, search)
- File upload endpoints

### 2. Database Performance Testing

Tests database performance with various query patterns:

```bash
# Navigate to backend directory
cd backend

# Run database performance tests
php ../scripts/db-performance-test.php
```

**Tests Included:**
- Connection performance
- Query performance (SELECT, INSERT, UPDATE, DELETE)
- Join performance
- Index performance
- Pagination performance
- Search performance
- Cache vs. database performance

### 3. WebSocket Performance Testing

Tests WebSocket connection performance and stability:

```bash
# Install WebSocket dependency
npm install ws

# Run WebSocket performance tests
node scripts/websocket-performance-test.js
```

**Tests Included:**
- Connection establishment time
- Message throughput
- Concurrent connections
- Connection stability
- Large message handling
- Reconnection performance

### 4. Performance Monitoring

Enable real-time performance monitoring:

```bash
# Enable performance monitoring in .env
PERFORMANCE_MONITORING_ENABLED=true
API_CACHE_ENABLED=true
QUERY_CACHE_ENABLED=true

# Register performance middleware in bootstrap/app.php
```

## Understanding Test Results

### Performance Metrics

Key metrics to monitor:

1. **Response Time**: Time taken to process requests
   - **Good**: < 500ms
   - **Acceptable**: 500ms - 1000ms
   - **Poor**: > 1000ms

2. **Requests Per Second (RPS)**: Number of requests processed per second
   - **Good**: > 100 RPS
   - **Acceptable**: 50-100 RPS
   - **Poor**: < 50 RPS

3. **Memory Usage**: Memory consumed during request processing
   - **Good**: < 10MB per request
   - **Acceptable**: 10-50MB per request
   - **Poor**: > 50MB per request

4. **Database Query Count**: Number of queries per request
   - **Good**: < 10 queries
   - **Acceptable**: 10-20 queries
   - **Poor**: > 20 queries

5. **Cache Hit Rate**: Percentage of requests served from cache
   - **Good**: > 80%
   - **Acceptable**: 50-80%
   - **Poor**: < 50%

### Reading Test Reports

Performance test results are saved in the `performance-results/` directory:

- `performance_report_TIMESTAMP.txt` - Detailed test logs
- `performance_summary_TIMESTAMP.txt` - Summary report with recommendations
- `db_performance_TIMESTAMP.json` - Database performance metrics
- `websocket_performance_TIMESTAMP.json` - WebSocket performance metrics
- `system_monitor_TIMESTAMP.txt` - System resource monitoring

### Example Report Analysis

```
========================================
TQRS Performance Testing Report
========================================
Date: 2024-01-15 14:30:00
Test Duration: 00:05:42

=== Test Configuration ===
Base URL: http://localhost:8000
Light Load: 10 requests
Medium Load: 50 requests
Heavy Load: 100 requests
Concurrency Levels: 1 5 10 20

=== Performance Warnings ===
     2 → Low RPS detected (8.5 < 10)
     1 → High response time detected (1250ms > 1000ms)

=== Performance Errors ===
     0 → 0 failed requests detected

=== Recommendations ===
1. Review endpoints with RPS < 10
2. Optimize endpoints with response time > 1000ms
3. Consider implementing caching for frequently accessed endpoints
4. Monitor database query performance
5. Implement rate limiting for public endpoints
```

## Performance Optimization

### 1. Database Optimization

Based on test results, optimize database performance:

```php
// Enable query caching
'query_cache' => [
    'enabled' => true,
    'ttl' => 3600, // 1 hour
],

// Optimize pagination
'pagination' => [
    'max_per_page' => 100,
    'default_per_page' => 15,
],

// Enable eager loading
'eager_loading' => [
    'enabled' => true,
    'default_relations' => [
        'blogs' => ['author', 'category', 'tags'],
    ],
],
```

### 2. API Response Caching

Implement caching for frequently accessed endpoints:

```php
// Cache public endpoints
'public_endpoints' => [
    'articles' => 3600,        // 1 hour
    'webinars' => 1800,        // 30 minutes
    'opportunities' => 900,     // 15 minutes
],
```

### 3. Rate Limiting

Implement rate limiting to prevent abuse:

```php
'rate_limiting' => [
    'enabled' => true,
    'api_limits' => [
        'public' => ['limit' => 100, 'window' => 60],
        'authenticated' => ['limit' => 200, 'window' => 60],
    ],
],
```

### 4. Memory Optimization

Optimize memory usage:

```php
'memory' => [
    'limit' => '256M',
    'gc_enabled' => true,
    'max_execution_time' => 30,
],
```

## Continuous Monitoring

### 1. Performance Alerts

Set up alerts for performance issues:

```bash
# Monitor slow requests
tail -f storage/logs/performance.log | grep "Slow request"

# Monitor memory usage
tail -f storage/logs/performance.log | grep "Memory"
```

### 2. Performance Dashboard

Access performance metrics via API:

```bash
# Get performance summary
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/performance/summary

# Get detailed metrics
curl -H "Authorization: Bearer TOKEN" \
  http://localhost:8000/api/performance/metrics?period=hour
```

### 3. Automated Testing

Set up automated performance testing:

```bash
# Add to crontab for daily testing
0 2 * * * /path/to/scripts/performance-test.sh >> /var/log/performance-tests.log 2>&1
```

## Troubleshooting

### Common Issues

1. **High Response Times**:
   - Check database query optimization
   - Verify caching is enabled
   - Monitor server resources

2. **Low RPS**:
   - Increase server resources
   - Optimize application code
   - Implement connection pooling

3. **Memory Issues**:
   - Check for memory leaks
   - Optimize data structures
   - Enable garbage collection

4. **Database Bottlenecks**:
   - Add database indexes
   - Optimize queries
   - Consider read replicas

### Performance Tuning Checklist

- [ ] Database indexes optimized
- [ ] Query caching enabled
- [ ] API response caching implemented
- [ ] Rate limiting configured
- [ ] Memory limits set appropriately
- [ ] Connection pooling enabled
- [ ] Static asset optimization
- [ ] CDN configuration (if applicable)
- [ ] Monitoring and alerting set up
- [ ] Regular performance testing scheduled

## Best Practices

1. **Regular Testing**: Run performance tests regularly, especially after code changes
2. **Baseline Metrics**: Establish baseline performance metrics for comparison
3. **Gradual Load Testing**: Start with light loads and gradually increase
4. **Monitor Production**: Use performance monitoring in production environment
5. **Optimize Based on Data**: Make optimization decisions based on actual performance data
6. **Test Realistic Scenarios**: Use realistic data volumes and user patterns
7. **Document Changes**: Keep track of optimizations and their impact

## Support

For performance-related issues or questions:

1. Check the performance test logs in `performance-results/`
2. Review the application logs in `storage/logs/`
3. Monitor system resources during tests
4. Consult this guide for optimization strategies
5. Contact the development team for assistance

---

**Remember**: Performance testing should be an ongoing process, not a one-time activity. Regular testing and monitoring ensure your application maintains optimal performance as it grows and evolves. 