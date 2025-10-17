#!/bin/bash

echo "Setting up Laravel project with Sail (Docker)..."

# Check if .env file exists, if not copy from .env.example
if [ ! -f ".env" ]; then
    echo "Creating .env file from example..."
    cp .env.example .env
fi

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "Error: Docker is not installed or not running."
    echo "Please install Docker Desktop (https://www.docker.com/products/docker-desktop/) and try again."
    exit 1
fi

# Check if vendor directory exists
if [ ! -d "vendor" ]; then
    echo "Vendor directory not found. Installing PHP dependencies using Docker container..."
    
    # Run composer install using Docker container
    echo "Running composer install in Docker container..."
    docker run --rm \
        -v "$(pwd)":/opt \
        -w /opt \
        composer:latest \
        composer install --ignore-platform-reqs
    
    if [ $? -eq 0 ]; then
        echo "PHP dependencies installed successfully!"
    else
        echo "Error: Failed to install PHP dependencies."
        exit 1
    fi
else
    echo "Vendor directory already exists. Skipping PHP dependency installation."
fi

# Generate application key using Docker container if not already set
echo "Checking if application key is set..."
if ! grep -q "APP_KEY=.*[a-zA-Z0-9]" .env; then
    echo "Generating application key using Docker container..."
    docker run --rm \
        -v "$(pwd)":/opt \
        -w /opt \
        --env-file .env \
        php:8.3-cli \
        bash -c "apt-get update && apt-get install -y git && php artisan key:generate"
    
    if [ $? -eq 0 ]; then
        echo "Application key generated successfully!"
    else
        echo "Warning: Failed to generate application key. You may need to generate it manually after setup."
    fi
else
    echo "Application key already set. Skipping key generation."
fi

# Publish Sail Docker files if docker-compose.yml doesn't exist
if [ ! -f "docker-compose.yml" ]; then
    echo "Publishing Sail configuration using Docker container..."
    docker run --rm \
        -v "$(pwd)":/opt \
        -w /opt \
        --env-file .env \
        php:8.3-cli \
        bash -c "apt-get update && apt-get install -y git && php artisan sail:install --with=mysql,redis,mailpit"
    
    if [ $? -eq 0 ]; then
        echo "Sail configuration published successfully!"
    else
        echo "Error: Failed to publish Sail configuration."
        exit 1
    fi
else
    echo "Docker-compose.yml already exists. Skipping Sail configuration."
fi

# Build and start Docker containers
echo "Building and starting Docker containers..."
./vendor/bin/sail up -d

# Wait for services to be ready
echo "Waiting for services to be ready..."
sleep 30

# Check if containers are running
echo "Checking container status..."
CONTAINERS_RUNNING=$(docker ps | grep sistem_mapi | wc -l)
if [ "$CONTAINERS_RUNNING" -lt 3 ]; then
    echo "Warning: Not all containers are running. Please check the status with 'docker ps'."
else
    echo "All containers are running!"
fi

# Install Node.js dependencies in container if node_modules doesn't exist
echo "Checking for Node.js dependencies..."
./vendor/bin/sail exec laravel.test bash -c "
    if [ ! -d \"node_modules\" ]; then
        echo 'Installing Node.js dependencies in container...'
        npm install
        echo 'Node.js dependencies installed!'
    else
        echo 'Node.js dependencies already installed.'
    fi
"

# Build frontend assets
echo "Building frontend assets..."
./vendor/bin/sail exec laravel.test npm run build

# Run clear-cache artisan commands
echo "Clearing application config & cache..."
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:cache

# Run key generate artisan commands
echo "Running database migrations..."
./vendor/bin/sail artisan key:generate

# Run database migrations
echo "Running database migrations..."
./vendor/bin/sail artisan migrate:fresh --force

# Run database db:seed
echo "Running database seed..."
./vendor/bin/sail artisan db:seed --force

echo ""
echo "Setup complete! Your Laravel application should be available at http://localhost:8001"
echo ""
echo "Important: If you encounter any issues with Node.js or npm, you may need to install them manually in the container:"
echo "  docker exec sistem_mapi-laravel.test-1 bash -c \"apt-get update && apt-get install -y ca-certificates curl gnupg && mkdir -p /etc/apt/keyrings && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && echo \\\"deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main\\\" | tee /etc/apt/sources.list.d/nodesource.list && apt-get update && apt-get install -y nodejs\""
echo ""
echo "Useful commands:"
echo " - View logs: ./vendor/bin/sail logs"
echo " - Stop services: ./vendor/bin/sail down"
echo " - Restart services: ./vendor/bin/sail restart"
echo " - Run Artisan commands: ./vendor/bin/sail artisan [command]"
echo " - Execute PHP commands: ./vendor/bin/sail php [command]"
echo " - Access the container: ./vendor/bin/sail shell"
echo " - Execute Node.js commands: ./vendor/bin/sail npm [command]"
echo ""
echo "Note: This setup does not require PHP, Composer, Node.js, or any other development tools installed on your host system."
echo "Everything runs inside Docker containers for consistent behavior across different environments."