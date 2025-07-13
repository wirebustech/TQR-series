#!/bin/bash

# TQRS Platform Deployment Script
# This script handles the deployment of the TQRS platform to production

set -e

echo "🚀 Starting TQRS Platform Deployment"
echo "====================================="

# Configuration
DEPLOY_ENV=${1:-production}
DEPLOY_PATH="/var/www/tqrs"
BACKUP_PATH="/var/backups/tqrs"
LOG_FILE="/var/log/tqrs-deploy.log"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${GREEN}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1" | tee -a "$LOG_FILE"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1" | tee -a "$LOG_FILE"
    exit 1
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1" | tee -a "$LOG_FILE"
}

# Check if running as root
if [[ $EUID -eq 0 ]]; then
   error "This script should not be run as root"
fi

# Check prerequisites
log "Checking prerequisites..."
command -v php >/dev/null 2>&1 || error "PHP is not installed"
command -v composer >/dev/null 2>&1 || error "Composer is not installed"
command -v node >/dev/null 2>&1 || error "Node.js is not installed"
command -v npm >/dev/null 2>&1 || error "npm is not installed"
command -v mysql >/dev/null 2>&1 || error "MySQL is not installed"

# Create backup directory if it doesn't exist
sudo mkdir -p "$BACKUP_PATH"

# Create backup of current deployment
if [ -d "$DEPLOY_PATH" ]; then
    log "Creating backup of current deployment..."
    BACKUP_NAME="backup-$(date +%Y%m%d-%H%M%S)"
    sudo cp -r "$DEPLOY_PATH" "$BACKUP_PATH/$BACKUP_NAME"
    log "Backup created: $BACKUP_PATH/$BACKUP_NAME"
fi

# Navigate to project directory
cd "$(dirname "$0")/.."

# Backend Deployment
log "Deploying backend..."
cd backend

# Install PHP dependencies
log "Installing PHP dependencies..."
composer install --no-dev --optimize-autoloader

# Set up environment
if [ ! -f .env ]; then
    log "Setting up environment configuration..."
    cp env-production.example .env
    php artisan key:generate --force
    warning "Please update .env with your production settings"
fi

# Database migrations
log "Running database migrations..."
php artisan migrate --force

# Clear and cache configuration
log "Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Set proper permissions
log "Setting file permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Frontend Deployment
log "Deploying frontend..."
cd ../frontend

# Install Node.js dependencies
log "Installing Node.js dependencies..."
npm ci --only=production

# Build frontend assets
log "Building frontend assets..."
npm run build

# Admin Portal Deployment
log "Deploying admin portal..."
cd ../admin

# Install Node.js dependencies
log "Installing Node.js dependencies..."
npm ci --only=production

# Build admin assets
log "Building admin assets..."
npm run build

# Copy files to deployment directory
log "Copying files to deployment directory..."
sudo rsync -av --delete \
    --exclude 'node_modules' \
    --exclude '.git' \
    --exclude '.env' \
    --exclude 'storage/logs' \
    --exclude 'storage/framework/cache' \
    --exclude 'storage/framework/sessions' \
    --exclude 'storage/framework/views' \
    ../ "$DEPLOY_PATH/"

# Set up symbolic links for shared directories
log "Setting up symbolic links..."
sudo ln -sf "$DEPLOY_PATH/storage/app/public" "$DEPLOY_PATH/public/storage"

# Restart services
log "Restarting services..."
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
sudo systemctl restart mysql

# Queue worker (if using)
if systemctl is-active --quiet tqrs-queue; then
    log "Restarting queue worker..."
    sudo systemctl restart tqrs-queue
fi

# WebSocket server (if using)
if systemctl is-active --quiet tqrs-websocket; then
    log "Restarting WebSocket server..."
    sudo systemctl restart tqrs-websocket
fi

# Health check
log "Performing health check..."
cd "$DEPLOY_PATH/backend"
php artisan inspire > /dev/null 2>&1 || error "Backend health check failed"

# Test API endpoint
curl -f -s "http://localhost/api/health" > /dev/null || error "API health check failed"

log "✅ Deployment completed successfully!"
log "🌐 Your TQRS platform is now live"

# Display deployment summary
echo
echo "📋 Deployment Summary"
echo "===================="
echo "Environment: $DEPLOY_ENV"
echo "Deploy Path: $DEPLOY_PATH"
echo "Backup Path: $BACKUP_PATH/$BACKUP_NAME"
echo "Log File: $LOG_FILE"
echo
echo "📝 Next Steps:"
echo "1. Update .env with production settings"
echo "2. Configure SSL certificates"
echo "3. Set up monitoring and logging"
echo "4. Configure backup automation"
echo "5. Set up CDN (if applicable)"
echo

log "Deployment script completed at $(date)" 