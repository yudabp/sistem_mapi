# Sistem MAPI - Docker Setup Complete Guide

This document provides a comprehensive guide on how to properly set up and run the Sistem MAPI project using Docker with Laravel Sail, overcoming common issues that may arise during the process.

## Overview

The project is now successfully running in Docker containers with all dependencies properly installed:
- PHP 8.3 with all required extensions
- MySQL 8.0 database
- Redis for caching
- Mailpit for email testing
- Node.js 22.x with NPM for frontend asset compilation
- All Laravel Sail services

## Prerequisites

Before starting, ensure you have:
- Docker Engine (20.10 or higher)
- Docker Compose (v2.0 or higher)

You do NOT need to have PHP, Node.js, NPM, MySQL, or any other development tools installed on your host system.

## Step-by-Step Setup

### 1. Clone the Project

```bash
git clone <repository-url>
cd sistem_mapi
```

### 2. Prepare Environment File

Copy the example environment file:
```bash
cp .env.example .env
```

### 3. Install Dependencies Using Standalone Composer Container

When you first clone the project, the `vendor` folder doesn't exist. Install dependencies using a standalone Docker container:

```bash
# Run composer install using a Docker container to create the vendor folder
docker run --rm -v $(pwd):/app -w /app composer:latest composer install
```

This command:
- Uses the official Composer Docker image
- Mounts your project directory to `/app` inside the container
- Runs `composer install` which creates the `vendor` folder and installs all dependencies
- After completion, the `vendor` folder will exist with all necessary files

### 4. Build and Start Services

After the vendor folder has been created, start all services:

```bash
# Using Sail script (recommended)
./vendor/bin/sail up -d
```

Or using Docker Compose directly:
```bash
docker compose up -d
```

### 5. Generate Application Key

```bash
# Using Sail
./vendor/bin/sail artisan key:generate

# Or using Docker Compose directly
docker compose exec laravel.test php artisan key:generate
```

### 6. Install Node.js Dependencies (Manual Installation)

During the build process, there might be network issues preventing Node.js installation. If this happens, install Node.js manually in the running container:

```bash
# Install Node.js in the running container
docker exec sistem_mapi-laravel.test-1 bash -c "apt-get update && apt-get install -y ca-certificates curl gnupg && mkdir -p /etc/apt/keyrings && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && echo \"deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main\" | tee /etc/apt/sources.list.d/nodesource.list && apt-get update && apt-get install -y nodejs"
```

Verify installation:
```bash
docker exec sistem_mapi-laravel.test-1 node --version
docker exec sistem_mapi-laravel.test-1 npm --version
```

### 7. Install Frontend Dependencies

```bash
# Install NPM dependencies
docker exec sistem_mapi-laravel.test-1 bash -c "cd /var/www/html && npm install"
```

### 8. Build Frontend Assets

```bash
# Build frontend assets with Vite
docker exec sistem_mapi-laravel.test-1 bash -c "cd /var/www/html && npm run build"
```

### 9. Run Database Migrations

```bash
# Using Sail
./vendor/bin/sail artisan migrate

# Or using Docker Compose directly
docker compose exec laravel.test php artisan migrate
```

### 10. (Optional) Seed Database

```bash
# Using Sail
./vendor/bin/sail artisan db:seed

# Or using Docker Compose directly
docker compose exec laravel.test php artisan db:seed
```

## Accessing the Application

After setup, the application will be available at:
- Web Application: http://localhost:8001
- Database: MySQL service accessible at port 3307
- Redis: Redis service accessible at port 6380
- Mailpit (Email Testing): http://localhost:8026

## Important Notes

### Working with Dependencies

All PHP and Node.js dependencies are installed inside the Docker containers:
- To install additional PHP packages: `./vendor/bin/sail composer require <package-name>`
- To install additional Node.js packages: `./vendor/bin/sail npm install <package-name>`
- To run PHP commands: `./vendor/bin/sail php <command>`
- To run Artisan commands: `./vendor/bin/sail artisan <command>`

### Alternative: Direct Docker Compose Commands

If for any reason the sail script is not available or you prefer to use Docker Compose directly:

1. Build and start services:
   ```bash
   docker compose up -d
   ```

2. Install PHP dependencies:
   ```bash
   docker compose exec laravel.test composer install
   ```

3. Generate application key:
   ```bash
   docker compose exec laravel.test php artisan key:generate
   ```

4. Run database migrations:
   ```bash
   docker compose exec laravel.test php artisan migrate
   ```

However, using `./vendor/bin/sail` is recommended as it's designed specifically for this purpose.

### Development Commands

When developing, run all commands through Sail (if available) or Docker Compose:

Using Sail (once vendor folder exists):
```bash
# Run tests
./vendor/bin/sail artisan test

# Build frontend assets during development
./vendor/bin/sail npm run dev

# Build frontend assets for production
./vendor/bin/sail npm run build

# Execute PHP commands
./vendor/bin/sail php artisan <command>

# Execute MySQL commands
./vendor/bin/sail mysql

# Execute Node.js commands
./vendor/bin/sail node <command>
# or
./vendor/bin/sail npx <command>
```

Using Docker Compose directly (always available):
```bash
# Run tests
docker compose exec laravel.test php artisan test

# Build frontend assets during development
docker compose exec laravel.test npm run dev

# Build frontend assets for production
docker compose exec laravel.test npm run build

# Execute Artisan commands
docker compose exec laravel.test php artisan <command>

# Execute PHP commands
docker compose exec laravel.test php <command>

# Execute MySQL commands (requires mysql client in container)
docker compose exec mysql mysql -u root -p

# Execute Node.js commands
docker compose exec laravel.test node <command>
# or
docker compose exec laravel.test npx <command>
```

The Sail commands are more convenient, but Docker Compose commands will always work regardless of whether the vendor folder exists.

### Accessing the Container

To access the application container directly:

```bash
# Using Sail
./vendor/bin/sail shell

# Or using Docker Compose
docker compose exec laravel.test bash
```

Once inside the container, you can run commands normally:

```bash
cd /var/www/html
php artisan list
npm run dev
composer install
```

### Installing Additional Tools

If you need additional tools inside the container, you can install them:

```bash
# Using Sail
./vendor/bin/sail exec laravel.test apt-get update
./vendor/bin/sail exec laravel.test apt-get install -y <package-name>

# Or using Docker Compose
docker compose exec laravel.test apt-get update
docker compose exec laravel.test apt-get install -y <package-name>
```

### Development Workflow

For full Docker development:

1. Start services: `./vendor/bin/sail up -d`
2. Run commands through Sail: `./vendor/bin/sail artisan <command>`
3. Access the app at http://localhost:8001
4. Stop services: `./vendor/bin/sail down`

## Troubleshooting Common Issues

### 1. Port Conflicts

If you encounter port conflicts, modify your `.env` file with alternative ports:

```env
APP_PORT=8001
FORWARD_DB_PORT=3307
FORWARD_REDIS_PORT=6380
FORWARD_MAILPIT_PORT=1026
FORWARD_MAILPIT_DASHBOARD_PORT=8026
```

Then restart the services:
```bash
./vendor/bin/sail down
./vendor/bin/sail up -d
```

### 2. Node.js Installation Failures

If Node.js fails to install during the build process due to network issues:

1. Manually install Node.js in the running container as shown in step 6
2. Proceed with npm install and asset building

### 3. Permission Issues

If you encounter permission issues with files:

```bash
# Fix permissions
docker compose exec laravel.test chown -R sail:sail /var/www/html
```

### 4. Database Connection Issues

If the application cannot connect to the database:

1. Check that MySQL container is running:
   ```bash
   docker compose ps
   ```

2. Verify database credentials in `.env` file match the container configuration:
   ```env
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=sistem_mapi
   DB_USERNAME=sail
   DB_PASSWORD=password
   ```

### 5. Clearing Cache

If you encounter issues, clear cache and restart:

```bash
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan view:clear
```

### 6. Rebuilding Containers

To completely rebuild containers:

```bash
./vendor/bin/sail down
./vendor/bin/sail build --no-cache
./vendor/bin/sail up -d
```

## Useful Commands

- View logs: `./vendor/bin/sail logs`
- Stop services: `./vendor/bin/sail down`
- Run Artisan commands: `./vendor/bin/sail artisan [command]`
- Execute PHP commands: `./vendor/bin/sail php [command]`
- Access the application container: `./vendor/bin/sail shell`
- Execute MySQL client: `./vendor/bin/sail mysql`
- Restart services: `./vendor/bin/sail restart`
- Execute Composer: `./vendor/bin/sail composer [command]`
- Execute NPM: `./vendor/bin/sail npm [command]`

## Summary

This Docker-first approach allows you to run the Sistem MAPI project without installing any dependencies on your host system:

- ✅ No need to install PHP on your host
- ✅ No need to install Composer on your host
- ✅ No need to install Node.js/NPM on your host
- ✅ No need to install MySQL on your host
- ✅ No need to install Redis on your host
- ✅ All dependencies are managed inside Docker containers

All development, testing, and dependency management can be done entirely within the Docker environment, ensuring consistent behavior across different development machines and platforms.