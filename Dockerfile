# Multi-stage build for TQRS Platform
FROM php:8.2-fpm as backend

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    mysql-client \
    redis-tools \
    supervisor \
    nginx \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy backend files
COPY backend/ /var/www/backend/
COPY database/ /var/www/database/

# Install PHP dependencies
RUN cd backend && composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/backend/storage /var/www/backend/bootstrap/cache
RUN chmod -R 775 /var/www/backend/storage /var/www/backend/bootstrap/cache

# Frontend build stage
FROM node:18-alpine as frontend

WORKDIR /app

# Copy frontend files
COPY frontend/package*.json ./
RUN npm ci --only=production

COPY frontend/ .
RUN npm run build

# Admin build stage
FROM node:18-alpine as admin

WORKDIR /app

# Copy admin files
COPY admin/package*.json ./
RUN npm ci --only=production

COPY admin/ .
RUN npm run build

# Final stage
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    mysql-client \
    redis-tools \
    supervisor \
    nginx \
    && docker-php-ext-configure gd \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    && pecl install redis \
    && docker-php-ext-enable redis

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy backend from backend stage
COPY --from=backend /var/www/backend /var/www/backend
COPY --from=backend /var/www/database /var/www/database

# Copy frontend build from frontend stage
COPY --from=frontend /app/dist /var/www/frontend/dist
COPY --from=frontend /app/assets /var/www/frontend/assets
COPY --from=frontend /app/components /var/www/frontend/components
COPY --from=frontend /app/pages /var/www/frontend/pages
COPY --from=frontend /app/index.html /var/www/frontend/

# Copy admin build from admin stage
COPY --from=admin /app/dist /var/www/admin/dist
COPY --from=admin /app/assets /var/www/admin/assets
COPY --from=admin /app/components /var/www/admin/components
COPY --from=admin /app/pages /var/www/admin/pages
COPY --from=admin /app/index.html /var/www/admin/

# Copy nginx configuration
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf

# Copy supervisor configuration
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Copy Docker entrypoint
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www/backend/storage /var/www/backend/bootstrap/cache

# Expose ports
EXPOSE 80 8080

# Start services
CMD ["/entrypoint.sh"] 