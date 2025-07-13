#!/bin/bash

# SSL Certificate Setup Script for TQRS Platform
# This script sets up SSL certificates using Let's Encrypt

set -e

echo "🔐 Setting up SSL certificates for TQRS Platform"
echo "==============================================="

# Configuration
DOMAIN=${1:-"tqrs.example.com"}
EMAIL=${2:-"admin@example.com"}
WEBROOT_PATH="/var/www/certbot"
CERT_PATH="/etc/letsencrypt/live/$DOMAIN"

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Logging function
log() {
    echo -e "${GREEN}[$(date '+%Y-%m-%d %H:%M:%S')]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
    exit 1
}

warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

# Check if domain is provided
if [ -z "$1" ]; then
    error "Usage: $0 <domain> [email]"
fi

log "Setting up SSL for domain: $DOMAIN"
log "Contact email: $EMAIL"

# Create webroot directory
log "Creating webroot directory..."
sudo mkdir -p "$WEBROOT_PATH"

# Check if certificates already exist
if [ -d "$CERT_PATH" ]; then
    warning "Certificates already exist for $DOMAIN"
    read -p "Do you want to renew them? (y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        log "Skipping certificate generation"
        exit 0
    fi
fi

# Generate certificates using certbot
log "Generating SSL certificates..."
sudo certbot certonly \
    --webroot \
    --webroot-path="$WEBROOT_PATH" \
    --email "$EMAIL" \
    --agree-tos \
    --no-eff-email \
    --force-renewal \
    -d "$DOMAIN"

# Verify certificate generation
if [ ! -f "$CERT_PATH/fullchain.pem" ] || [ ! -f "$CERT_PATH/privkey.pem" ]; then
    error "Certificate generation failed!"
fi

log "SSL certificates generated successfully!"

# Set up automatic renewal
log "Setting up automatic renewal..."
sudo crontab -l 2>/dev/null | grep -v "certbot renew" | sudo crontab -
echo "0 12 * * * /usr/bin/certbot renew --quiet" | sudo crontab -

log "✅ SSL setup completed successfully!"
log "📋 Certificate details:"
log "   - Domain: $DOMAIN"
log "   - Certificate path: $CERT_PATH"
log "   - Expires: $(sudo openssl x509 -enddate -noout -in "$CERT_PATH/cert.pem" | cut -d= -f2)"

echo
echo "📝 Next steps:"
echo "1. Update your DNS records to point to this server"
echo "2. Update nginx configuration with the correct domain"
echo "3. Restart nginx: sudo systemctl restart nginx"
echo "4. Test your site: https://$DOMAIN"
echo

log "SSL setup script completed at $(date)" 