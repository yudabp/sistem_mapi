# Running Sistem MAPI in Docker (Full Containerized Setup)

This guide explains how to run the Sistem MAPI project entirely within Docker containers without requiring PHP, Composer, NPM, MySQL or other development tools installed on your host system.

When you clone this project, you'll notice that several directories like `vendor/` and `node_modules/` are not included in the repository. This is normal. In this Docker-first setup, all dependencies are installed inside the Docker containers, not on your host machine. This allows you to run the project without having PHP, Composer, Node.js, or other tools installed locally.

## Prerequisites

- Docker Engine (20.10 or higher)
- Docker Compose (v2.0 or higher)

## Zero-Dependency Setup

### 1. Clone the project

```bash
git clone <repository-url>
cd sistem_mapi
```

### 2. Copy the environment file

```bash
cp .env.example .env
```

### 3. Install dependencies using a standalone Composer container

When you first clone the project, the `vendor` folder doesn't exist yet, but the `docker-compose.yml` file is already present. However, Docker Compose cannot build the application container because it references files in `vendor/laravel/sail/runtimes/8.3` which don't exist yet.

This creates a "chicken and egg" problem. The solution is to install dependencies using a standalone Docker container with Composer:

```bash
# Run composer install using a Docker container to create the vendor folder
docker run --rm -v $(pwd):/app -w /app composer:latest composer install
```

This command will:
- Use the official Composer Docker image
- Mount your project directory to `/app` inside the container
- Run `composer install` which creates the `vendor` folder and installs all dependencies including Laravel Sail
- After completion, the `vendor` folder will exist with all necessary files

### 4. Start the services

After the vendor folder has been created, you can start all services:

Using Sail script (now available):
```bash
./vendor/bin/sail up -d
```

Or using Docker Compose directly:
```bash
docker compose up -d
```

### 5. Generate application key

```bash
./vendor/bin/sail artisan key:generate
```

Or using Docker Compose directly:
```bash
docker compose exec laravel.test php artisan key:generate
```

### 6. Install Node.js dependencies

```bash
./vendor/bin/sail npm install
```

Or using Docker Compose directly:
```bash
docker compose exec laravel.test npm install
```

### 7. Build frontend assets

```bash
./vendor/bin/sail npm run build
```

Or using Docker Compose directly:
```bash
docker compose exec laravel.test npm run build
```

### 8. Run database migrations

```bash
./vendor/bin/sail artisan migrate
```

Or using Docker Compose directly:
```bash
docker compose exec laravel.test php artisan migrate
```

### 9. (Optional) Seed the database

```bash
./vendor/bin/sail artisan db:seed
```

Or using Docker Compose directly:
```bash
docker compose exec laravel.test php artisan db:seed
```

## Accessing the Application

After setup, the application will be available at:
- Web Application: http://localhost:8000
- Database: mysql service (accessible via Sail container)
- Redis: redis service (accessible via Sail container)
- Mailpit (Email Testing): http://localhost:8025

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

4. Install Node.js dependencies:
   ```bash
   docker compose exec laravel.test npm install
   ```

5. Run database migrations:
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
./vendor/bin/sail shell
```

Or using Docker Compose:
```bash
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
./vendor/bin/sail exec laravel.test apt-get update
./vendor/bin/sail exec laravel.test apt-get install -y <package-name>
```

Or using Docker Compose:
```bash
docker compose exec laravel.test apt-get update
docker compose exec laravel.test apt-get install -y <package-name>
```

### Development Workflow

For full Docker development:

1. Start services: `./vendor/bin/sail up -d`
2. Run commands through Sail: `./vendor/bin/sail artisan <command>`
3. Access the app at http://localhost:8000
4. Stop services: `./vendor/bin/sail down`

## Troubleshooting

### Ports Already in Use

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

### Clearing Cache

If you encounter issues, clear cache and restart:

```bash
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan view:clear
```

### Rebuilding Containers

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

## Performance Tips

1. On Linux, consider using Docker volumes for better performance:
   ```env
   WWWUSER=1000
   ```

2. For faster builds, enable Docker BuildKit:
   ```bash
   export DOCKER_BUILDKIT=1
   ```

3. Consider using Sail's optimized images for production-like staging environments.

## Summary

This Docker-first approach allows you to run the Sistem MAPI project without installing any dependencies on your host system:

- ✅ No need to install PHP on your host
- ✅ No need to install Composer on your host
- ✅ No need to install Node.js/NPM on your host
- ✅ No need to install MySQL on your host
- ✅ No need to install Redis on your host
- ✅ All dependencies are managed inside Docker containers

All development, testing, and dependency management can be done entirely within the Docker environment, ensuring consistent behavior across different development machines and platforms.