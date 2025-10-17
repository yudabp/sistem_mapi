# Setup Script for Sistem MAPI with Docker

This document explains how to use the `setup-sail.sh` script to automatically set up the Sistem MAPI project using Docker without requiring any development tools installed on your host system.

## Prerequisites

Before running the setup script, ensure you have:

1. **Docker Desktop** (for Windows/Mac) or **Docker Engine** (for Linux) installed and running
   - Docker version 20.10 or higher
   - Docker Compose version 2.0 or higher

You do **NOT** need to have any of the following installed on your host system:
- PHP
- Composer
- Node.js
- NPM
- MySQL
- Redis

All dependencies will be handled within Docker containers.

## How to Use the Setup Script

### 1. Make the Script Executable

If you haven't already, make the script executable:

```bash
chmod +x setup-sail.sh
```

### 2. Run the Setup Script

Simply execute the script:

```bash
./setup-sail.sh
```

### 3. What the Script Does

The script will automatically perform the following steps:

1. **Check for .env file** - Creates one from `.env.example` if it doesn't exist
2. **Verify Docker installation** - Ensures Docker is installed and running
3. **Install PHP dependencies** - Uses a Docker container with Composer to install all PHP packages
4. **Generate application key** - Generates the Laravel application key using a Docker container
5. **Publish Sail configuration** - Sets up Docker Compose files for the project
6. **Start Docker containers** - Builds and starts all required services (MySQL, Redis, Mailpit, Laravel app)
7. **Install Node.js dependencies** - Installs frontend dependencies inside the container
8. **Build frontend assets** - Compiles Vue.js/JavaScript assets using Vite
9. **Run database migrations** - Sets up the database schema

### 4. Accessing the Application

After the script completes successfully, you can access:

- **Web Application**: http://localhost:8001
- **Mailpit (Email Testing)**: http://localhost:8026
- **MySQL Database**: Port 3307
- **Redis**: Port 6380

## Troubleshooting

### Docker Not Installed

If you see the error "Docker is not installed or not running":

1. Install Docker Desktop (Windows/Mac): https://www.docker.com/products/docker-desktop/
2. Install Docker Engine (Linux): Follow the official Docker installation guide for your distribution
3. Make sure Docker is running before executing the script

### Permission Issues

On Linux systems, you might encounter permission issues. If this happens:

```bash
# Add your user to the docker group
sudo usermod -aG docker $USER

# Log out and log back in, or run:
newgrp docker

# Then try running the script again
./setup-sail.sh
```

### Port Conflicts

If you see port conflict errors:

1. Edit the `.env` file and change the port mappings:
   ```env
   APP_PORT=8001        # Change from 8000 if needed
   FORWARD_DB_PORT=3307  # Change from 3306 if needed
   FORWARD_REDIS_PORT=6380  # Change from 6379 if needed
   FORWARD_MAILPIT_PORT=1026  # Change from 1025 if needed
   FORWARD_MAILPIT_DASHBOARD_PORT=8026  # Change from 8025 if needed
   ```

2. Stop any existing containers:
   ```bash
   ./vendor/bin/sail down
   ```

3. Run the setup script again:
   ```bash
   ./setup-sail.sh
   ```

### Node.js Installation Issues

If you encounter issues with Node.js installation during the setup:

1. The script includes instructions for manual Node.js installation in the container:
   ```bash
   docker exec sistem_mapi-laravel.test-1 bash -c "apt-get update && apt-get install -y ca-certificates curl gnupg && mkdir -p /etc/apt/keyrings && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && echo \"deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main\" | tee /etc/apt/sources.list.d/nodesource.list && apt-get update && apt-get install -y nodejs"
   ```

2. Then install and build frontend dependencies:
   ```bash
   ./vendor/bin/sail exec laravel.test npm install
   ./vendor/bin/sail exec laravel.test npm run build
   ```

### Re-running the Setup Script

If you need to re-run the setup script:

1. Stop existing containers:
   ```bash
   ./vendor/bin/sail down
   ```

2. Optionally, remove existing vendor directory to reinstall PHP dependencies:
   ```bash
   rm -rf vendor
   ```

3. Run the setup script again:
   ```bash
   ./setup-sail.sh
   ```

## Advanced Usage

### Customizing Services

The script uses the default Sail services (MySQL, Redis, Mailpit). If you want to modify which services are included:

1. Edit the script and modify this line:
   ```bash
   php artisan sail:install --with=mysql,redis,mailpit
   ```

2. You can change it to include other services:
   ```bash
   php artisan sail:install --with=mysql,redis,mailpit,selenium
   ```

### Manual Steps After Setup

After the script completes, you might want to:

1. **Seed the database** (if needed):
   ```bash
   ./vendor/bin/sail artisan db:seed
   ```

2. **Create a user account**:
   ```bash
   ./vendor/bin/sail artisan tinker
   ```

3. **Run tests**:
   ```bash
   ./vendor/bin/sail artisan test
   ```

## How It Works

The script is designed to work without host dependencies by:

1. **Using Docker containers for PHP commands** - Instead of requiring PHP on the host, it uses `docker run` with PHP Docker images
2. **Using Composer Docker image** - For installing PHP dependencies without requiring Composer on the host
3. **Executing all Laravel commands in containers** - Once Sail is set up, all commands are run through `./vendor/bin/sail`
4. **Installing Node.js inside the container** - Frontend dependencies are handled entirely within the Docker environment

This approach ensures:
- **Consistent environment** - Same setup works on Windows, Mac, and Linux
- **No host dependency conflicts** - Won't interfere with existing PHP/Node.js installations
- **Easy onboarding** - New developers can get started with just Docker installed
- **Reproducible builds** - Everyone uses the same containerized environment

## Benefits

1. **Zero Host Dependencies** - No need to install PHP, Composer, Node.js, or databases locally
2. **Cross-Platform Compatibility** - Works identically on Windows, Mac, and Linux
3. **Version Consistency** - All team members use identical versions of all tools
4. **Easy Cleanup** - Simply run `./vendor/bin/sail down` to stop all services
5. **Production Parity** - Development environment closely matches production

## Additional Commands

After setup, you can use these commands for ongoing development:

```bash
# Start services
./vendor/bin/sail up -d

# Stop services
./vendor/bin/sail down

# View logs
./vendor/bin/sail logs

# Run Artisan commands
./vendor/bin/sail artisan [command]

# Run PHP commands
./vendor/bin/sail php [command]

# Run NPM commands
./vendor/bin/sail npm [command]

# Access the application container
./vendor/bin/sail shell

# Run database migrations
./vendor/bin/sail artisan migrate

# Run database seeds
./vendor/bin/sail artisan db:seed

# Run tests
./vendor/bin/sail artisan test
```

This containerized approach eliminates the common "works on my machine" problems and ensures consistent behavior across all development environments.