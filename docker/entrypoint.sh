#!/bin/bash

set -e

echo "🚀 Starting TQRS Platform Container"
echo "=================================="

# Wait for database to be ready
echo "⏳ Waiting for database connection..."
until php -r "
try {
    \$pdo = new PDO('mysql:host=database;dbname=tqrs', 'tqrs_user', 'tqrs_password');
    echo 'Database connection successful!';
} catch (PDOException \$e) {
    exit(1);
}
"
do
    echo "Database is unavailable - sleeping"
    sleep 5
done

# Navigate to backend directory
cd /var/www/backend

# Set up environment if it doesn't exist
if [ ! -f .env ]; then
    echo "📝 Setting up environment configuration..."
    cp env-production.example .env
    php artisan key:generate --force
fi

# Run database migrations
echo "🔄 Running database migrations..."
php artisan migrate --force

# Seed database if needed
if [ "$SEED_DATABASE" = "true" ]; then
    echo "🌱 Seeding database..."
    php artisan db:seed --force
fi

# Clear and optimize caches
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Link storage
echo "🔗 Linking storage..."
php artisan storage:link

# Set proper permissions
echo "🔒 Setting file permissions..."
chown -R www-data:www-data /var/www/backend/storage /var/www/backend/bootstrap/cache
chmod -R 775 /var/www/backend/storage /var/www/backend/bootstrap/cache

# Create log directory
mkdir -p /var/log/supervisor

# Start supervisor (manages all processes)
echo "🎯 Starting services..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf 