#!/bin/bash

echo "Setting up Laravel project with Sail (Docker)..."

# Check if .env file exists, if not copy from .env.example
if [ ! -f ".env" ]; then
    echo "Creating .env file from example..."
    cp .env.example .env
fi

# Install PHP dependencies using Composer
echo "Installing PHP dependencies..."
composer install

# Generate application key
echo "Generating application key..."
php artisan key:generate

# Install Node.js dependencies
echo "Installing Node.js dependencies..."
npm install

# Build frontend assets
echo "Building frontend assets..."
npm run build

# Publish Sail Docker files
echo "Publishing Sail configuration..."
php artisan sail:install --with=mysql,redis,mailpit

# Build and start Docker containers
echo "Building and starting Docker containers..."
./vendor/bin/sail up -d

# Wait for services to be ready
echo "Waiting for services to be ready..."
sleep 20

# Run database migrations
echo "Running database migrations..."
./vendor/bin/sail artisan migrate --force

echo "Setup complete! Your Laravel application should be available at http://localhost:8000"
echo "To view logs: ./vendor/bin/sail logs"
echo "To stop the services: ./vendor/bin/sail down"
echo ""
echo "Additional Sail commands:"
echo " - Run Artisan commands: ./vendor/bin/sail artisan [command]"
echo " - Execute PHP commands: ./vendor/bin/sail php [command]"
echo " - Access the container: ./vendor/bin/sail shell"