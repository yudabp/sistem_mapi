#!/bin/bash

echo "Setting up Laravel project with Docker..."

# Create the necessary directories if they don't exist
mkdir -p .docker

# Build and start the services
echo "Building and starting Docker services..."
docker-compose up -d --build

# Wait for the database to be ready
echo "Waiting for database to be ready..."
sleep 15

# Install Laravel dependencies if not already installed
if [ ! -d "vendor" ]; then
    echo "Installing PHP dependencies..."
    docker-compose exec app composer install
fi

# Generate application key
echo "Generating application key..."
docker-compose exec app php artisan key:generate

# Run database migrations
echo "Running database migrations..."
docker-compose exec app php artisan migrate --force

# Install Node.js dependencies if not already installed
if [ ! -d "node_modules" ]; then
    echo "Installing Node.js dependencies..."
    docker-compose exec app npm install
fi

# Build assets
echo "Building assets..."
docker-compose exec app npm run build

# Set proper permissions
echo "Setting proper folder permissions..."
docker-compose exec app chown -R www-data:www-data /var/www/storage
docker-compose exec app chown -R www-data:www-data /var/www/bootstrap/cache

echo "Setup complete! Your Laravel application should be available at http://localhost:8000"
echo "To view logs: docker-compose logs -f"
echo "To stop the services: docker-compose down"