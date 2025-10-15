# Sistem MAPI - Docker Setup with Laravel Sail

This project is configured to run with Laravel Sail, which provides a Docker-based development environment. Laravel Sail is already included in your project as a development dependency.

## Prerequisites

- Docker Engine (20.10 or higher)
- Docker Compose (v2.0 or higher)
- PHP 8.2+
- Composer
- Node.js & npm

## Quick Setup with Sail

1. Make sure you have Docker installed and running on your system
2. Navigate to this project directory
3. Run the automated setup script:

```bash
./setup-sail.sh
```

The script will:
- Install PHP dependencies with Composer
- Generate the application key
- Install Node.js dependencies
- Build frontend assets
- Publish Sail Docker configuration
- Build and start all Docker services
- Run database migrations

## Manual Setup with Sail

If you prefer to manually set up the project with Sail, follow these steps:

1. Install PHP dependencies:
```bash
composer install
```

2. Generate the application key:
```bash
php artisan key:generate
```

3. Install Node.js dependencies:
```bash
npm install
```

4. Build frontend assets:
```bash
npm run build
```

5. Publish Sail configuration (if not already done):
```bash
php artisan sail:install --with=mysql,redis,mailpit
```

6. Build and start the services:
```bash
./vendor/bin/sail up -d
```

7. Run database migrations:
```bash
./vendor/bin/sail artisan migrate
```

## Accessing the Application

After setup, the application will be available at:
- Web Application: http://localhost:8000
- Database: mysql service (accessible via Sail container)
- Redis: redis service (accessible via Sail container)
- Mailpit (Email Testing): http://localhost:8025

## Useful Sail Commands

- View logs: `./vendor/bin/sail logs`
- Stop services: `./vendor/bin/sail down`
- Run Artisan commands: `./vendor/bin/sail artisan [command]`
- Execute PHP commands: `./vendor/bin/sail php [command]`
- Access the application container: `./vendor/bin/sail shell`
- Execute MySQL client: `./vendor/bin/sail mysql`
- Restart services: `./vendor/bin/sail restart`

## Sail Services

This setup includes:
- **laravel.test**: PHP 8.3 with Apache web server running your Laravel application
- **mysql**: MySQL database server
- **redis**: Redis caching server
- **mailpit**: Mailpit email testing service

## Environment Configuration

The application uses the `.env` file with the following important settings:
- Database connection is configured to use the Sail mysql service
- Redis is configured to use the Sail redis service
- The application URL is set to http://localhost:8000

### Port Configuration for Sail

If you encounter port conflicts (especially when you have Redis, MySQL or other services running on your host system), you can customize the ports used by Sail services. Add these variables to your `.env` file:

```env
# Sail Port Configuration
APP_PORT=8001                 # Change if port 8000 is already in use
FORWARD_DB_PORT=3307          # Change if port 3306 is already in use
FORWARD_REDIS_PORT=6380       # Change if port 6379 is already in use
FORWARD_MAILPIT_PORT=1026     # Change if port 1025 is already in use
FORWARD_MAILPIT_DASHBOARD_PORT=8026  # Change if port 8025 is already in use
```

When you change these ports, remember to access your application using the new port numbers (e.g., http://localhost:8001 if you set APP_PORT=8001).

After changing these settings, run `./vendor/bin/sail down` and `./vendor/bin/sail up -d` to apply the new port configuration.

## Development Tips

- To add more services later, you can run commands like: `php artisan sail:add redis` or `php artisan sail:add meilisearch`
- Run tests in the Docker container with: `./vendor/bin/sail artisan test`
- Debug PHP with Xdebug by running: `./vendor/bin/sail debug [command]` (requires Xdebug configuration)

## Troubleshooting

If you encounter issues:
1. Ensure Docker is running on your system
2. Check if ports 8000, 3306, 6379, 8025 are available
3. Check logs with `./vendor/bin/sail logs`
4. If containers fail to start, try `./vendor/bin/sail down` and then `./vendor/bin/sail up -d`