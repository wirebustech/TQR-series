# TQRS Platform Deployment Guide

## 🚀 Overview

This guide provides comprehensive instructions for deploying the TQRS (The Qualitative Research Series) platform to production. The platform supports multiple deployment methods including traditional server deployment and containerized deployment using Docker.

## 📋 Prerequisites

### System Requirements
- **Operating System**: Ubuntu 20.04+ or CentOS 8+
- **CPU**: Minimum 2 cores (4+ recommended)
- **Memory**: Minimum 4GB RAM (8GB+ recommended)
- **Storage**: Minimum 20GB available space
- **Network**: Static IP address and domain name

### Software Dependencies
- **PHP**: 8.2 or higher
- **MySQL**: 8.0 or higher
- **Redis**: 6.0 or higher
- **Node.js**: 18.0 or higher
- **Nginx**: 1.18 or higher
- **Composer**: 2.0 or higher
- **Docker**: 20.10+ (for containerized deployment)
- **Docker Compose**: 1.29+ (for containerized deployment)

## 🔧 Deployment Methods

### Method 1: Traditional Server Deployment

#### Step 1: Prepare the Server
```bash
# Update system packages
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server redis-server php8.2-fpm php8.2-mysql php8.2-redis php8.2-xml php8.2-mbstring php8.2-curl php8.2-gd php8.2-zip unzip curl wget git

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

#### Step 2: Configure Database
```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p << EOF
CREATE DATABASE tqrs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'tqrs_user'@'localhost' IDENTIFIED BY 'your_secure_password';
GRANT ALL PRIVILEGES ON tqrs.* TO 'tqrs_user'@'localhost';
FLUSH PRIVILEGES;
EOF
```

#### Step 3: Deploy the Application
```bash
# Clone the repository
git clone https://github.com/your-org/tqrs-platform.git /var/www/tqrs
cd /var/www/tqrs

# Run the deployment script
./scripts/deploy.sh production

# Configure environment variables
sudo nano /var/www/tqrs/backend/.env
```

#### Step 4: Configure SSL
```bash
# Install certbot
sudo apt install -y certbot python3-certbot-nginx

# Generate SSL certificate
./scripts/setup-ssl.sh your-domain.com your-email@example.com
```

### Method 2: Docker Deployment

#### Step 1: Install Docker
```bash
# Install Docker and Docker Compose
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER

# Install Docker Compose
sudo curl -L "https://github.com/docker/compose/releases/download/v2.23.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

#### Step 2: Configure Environment
```bash
# Clone the repository
git clone https://github.com/your-org/tqrs-platform.git
cd tqrs-platform

# Create environment file
cp backend/env-production.example backend/.env

# Edit environment configuration
nano backend/.env
```

#### Step 3: Deploy with Docker
```bash
# Build and start containers
docker-compose up -d

# Check container status
docker-compose ps

# View logs
docker-compose logs -f app
```

## 🔐 Security Configuration

### SSL/TLS Setup
```bash
# Generate SSL certificate (if not using Docker)
./scripts/setup-ssl.sh your-domain.com admin@your-domain.com

# Configure automatic renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

### Firewall Configuration
```bash
# Configure UFW firewall
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### Security Headers
The Nginx configuration includes comprehensive security headers:
- HSTS (HTTP Strict Transport Security)
- X-Frame-Options
- X-Content-Type-Options
- X-XSS-Protection
- Content Security Policy
- Referrer Policy

## 📊 Monitoring and Maintenance

### Health Checks
```bash
# Check application health
curl -f http://your-domain.com/api/health

# Check database connectivity
docker-compose exec app php artisan tinker
# > DB::connection()->getPdo();
```

### Log Monitoring
```bash
# Application logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# Laravel logs
tail -f /var/www/tqrs/backend/storage/logs/laravel.log

# Docker logs
docker-compose logs -f app
```

### Backup Strategy
```bash
# Database backup
mysqldump -u tqrs_user -p tqrs > backup_$(date +%Y%m%d_%H%M%S).sql

# File backup
tar -czf tqrs_backup_$(date +%Y%m%d_%H%M%S).tar.gz /var/www/tqrs
```

## 🔄 Updates and Maintenance

### Application Updates
```bash
# Pull latest changes
git pull origin main

# Run deployment script
./scripts/deploy.sh production

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Database Migrations
```bash
# Run migrations
php artisan migrate --force

# Rollback if needed
php artisan migrate:rollback
```

## 🐛 Troubleshooting

### Common Issues

#### 1. Database Connection Error
```bash
# Check database service
sudo systemctl status mysql

# Test connection
mysql -u tqrs_user -p tqrs -e "SELECT 1"
```

#### 2. Permission Issues
```bash
# Fix file permissions
sudo chown -R www-data:www-data /var/www/tqrs/backend/storage
sudo chmod -R 775 /var/www/tqrs/backend/storage
```

#### 3. SSL Certificate Issues
```bash
# Check certificate validity
openssl x509 -in /etc/letsencrypt/live/your-domain.com/cert.pem -text -noout

# Renew certificate
sudo certbot renew --force-renewal
```

#### 4. Queue Worker Issues
```bash
# Restart queue workers
sudo systemctl restart tqrs-queue

# Check queue status
php artisan queue:work --once
```

## 🌍 Environment-Specific Configurations

### Development Environment
```bash
# Set environment to development
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug
```

### Staging Environment
```bash
# Set environment to staging
APP_ENV=staging
APP_DEBUG=false
LOG_LEVEL=info
```

### Production Environment
```bash
# Set environment to production
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=warning
```

## 📞 Support

### Getting Help
- **Documentation**: Check the `/docs` directory for detailed documentation
- **Logs**: Always check application and server logs for error details
- **GitHub Issues**: Report bugs and request features on GitHub
- **Community**: Join the TQRS community for support and discussions

### Emergency Procedures
```bash
# Quick rollback
git checkout previous_stable_tag
./scripts/deploy.sh production

# Emergency maintenance mode
php artisan down --message="Under maintenance"
php artisan up  # To bring back online
```

## 🎯 Performance Optimization

### Database Optimization
```bash
# Optimize database
php artisan optimize:db

# Index optimization
php artisan db:monitor
```

### Cache Configuration
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Warm up caches
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### CDN Setup
Configure a Content Delivery Network (CDN) for static assets:
1. Set up CloudFlare or AWS CloudFront
2. Update `APP_URL` in `.env` to use CDN for assets
3. Configure asset URLs in the application

---

**🎉 Congratulations! Your TQRS platform is now deployed and ready for production use.**

For additional support and documentation, visit the [TQRS Platform Documentation](https://docs.tqrs.example.com). 