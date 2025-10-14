# Free Tailwind & Laravel admin dashboard template

![Mosaic TailwindCSS template preview](https://github.com/cruip/laravel-tailwindcss-admin-dashboard-template/assets/2683512/68bf8c3d-6525-4565-b6f8-c81611b9c1eb)

**Mosaic Lite Laravel** is a responsive admin dashboard template built on top of Tailwind CSS and fully coded in Laravel Jetstream. This template is a great starting point for anyone who wants to create a user interface for SaaS products, administrator dashboards, modern web apps, and more.
Use it for whatever you want, and be sure to reach us out on [Twitter](https://twitter.com/Cruip_com) if you build anything cool/useful with it.

**UPDATE 2025-02-02** Added Tailwind v4 support!

Created and maintained with ❤️ by [Cruip.com](https://cruip.com/).

## Live demo

Check a live demo here 👉️ [https://mosaic.cruip.com/](https://mosaic.cruip.com/?template=laravel)

## Mosaic Pro

[![Mosaic Tailwind Admin Template](https://github.com/cruip/tailwind-dashboard-template/assets/2683512/2b4d0fae-bb07-4229-8a8a-48005f2f33cb)](https://cruip.com/mosaic/)

## Design files

If you need the design files, you can download them from Figma's Community 👉 https://bit.ly/3sigqHe

## Table of contents

* [Installation](#installation)
  * [Prerequisites](#prerequisites)
  * [Setup for Development](#setup-for-development)
  * [Setup for Production](#setup-for-production)
  * [Additional Configuration Notes](#additional-configuration-notes)
  * [Common Commands](#common-commands)
* [Credits](#credits)
* [Terms and License](#terms-and-license)
* [About Us](#about-us)
* [Stay in the loop](#stay-in-the-loop)

## Installation

This project was built with [Laravel Jetstream](https://jetstream.laravel.com/) and [Livewire + Blade](https://jetstream.laravel.com/2.x/introduction.html#livewire-blade) as Stack.

### Prerequisites

Before you begin, make sure you have the following installed on your system:
- PHP >= 8.1
- Composer 
- Node.js >= 16.x
- npm or pnpm
- MySQL / PostgreSQL / SQLite
- Git

### Setup for Development

#### 1. Clone the repository
```bash
git clone <repository-url>
cd sistem_mapi
```

#### 2. Copy the environment file
```bash
cp .env.example .env
```

#### 3. Configure the .env file
Update the following values in your `.env` file:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# For mail configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"
```

#### 4. Generate the application key
```bash
php artisan key:generate
```

#### 5. Install PHP dependencies
```bash
composer install
```

#### 6. Install Node.js dependencies
```bash
npm install
# or if using pnpm
pnpm install
```

#### 7. Run database migrations
```bash
php artisan migrate
```

#### 8. Generate test data (optional)
```bash
php artisan db:seed
```

#### 9. Compile front-end assets for development
```bash
npm run dev
# or for production build
npm run build
```

#### 10. Start the development server
```bash
php artisan serve
```

The application will be accessible at `http://127.0.0.1:8000`

### Setup for Production

#### 1. Environment Configuration

Set the following environment variables:
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

#### 2. Install dependencies
```bash
composer install --no-dev --optimize-autoloader
npm ci --production
```

#### 3. Clear and cache configuration
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 4. Run database migrations
```bash
php artisan migrate --force
```

#### 5. Compile assets for production
```bash
npm run build
```

#### 6. Set proper permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

#### 7. Configure your web server

For Apache, ensure the following in your virtual host:
```apache
<VirtualHost *:80>
    ServerName yourdomain.com
    DocumentRoot /path/to/your/project/public

    <Directory /path/to/your/project/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

For Nginx:
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/your/project/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Additional Configuration Notes

- **Cache Configuration**: In production, consider using Redis or Memcached for better performance
- **Queue Configuration**: For background jobs, configure a queue driver like Redis or database
- **Mail Configuration**: Set up SMTP settings for sending emails
- **SSL**: Always use HTTPS in production environments
- **Security**: Keep your dependencies updated and monitor for security vulnerabilities

### Common Commands

- **Clear caches**: `php artisan cache:clear && php artisan config:clear && php artisan route:clear && php artisan view:clear`
- **Optimize installation**: `php artisan optimize` or `php artisan config:cache && php artisan route:cache && php artisan view:cache`
- **Run cron jobs**: Set up a cron job as required by Laravel: `* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1`
- **Backup database**: `php artisan backup:run`
- **Check application status**: `php artisan down` / `php artisan up`


## Credits

- [Nucleo](https://nucleoapp.com/)

## Terms and License

- License 👉 [https://cruip.com/terms/](https://cruip.com/terms/).
- Copyright 2022 [Cruip](https://cruip.com/).
- Use it for personal and commercial projects, but please don’t republish, redistribute, or resell the template.
- Attribution is not required, although it is really appreciated.

## About Us

We're an Italian developer/designer duo creating high-quality design/code resources for developers, makers, and startups.

## Stay in the loop

If you would like to know when we release new resources, you can follow [@pacovitiello](https://x.com/pacovitiello) and [@DavidePacilio](https://x.com/DavidePacilio) on X, or you can subscribe to our [newsletter](https://cruip.com/newsletter/).
