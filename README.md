# Deployment Guide: ClientPerKlant

Follow these steps to deploy the application to a production server (Ubuntu/Nginx/MySQL).
### Prerequisites
*   PHP 8.3 or higher
*   Composer 8.2
*   Node.js & NPM
* 
### 1. Clone Repository
Navigate to the web directory and clone the source code.

```bash
cd /var/www
sudo git clone https://github.com/pico-inno/ClientPerKlant.git client-per-klant
cd client-per-klant
```

### 2. Install Dependencies
Install PHP dependencies (backend) and Node dependencies (frontend).

```bash
composer install --optimize-autoloader


npm install
npm run build
```

### 3. Environment Configuration
Set up the environment variables.

```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file (`nano .env`) to match your production credentials:

```ini
APP_ENV=production
APP_DEBUG=false
APP_URL=https://example.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=client_per_klant_db
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password

QUEUE_CONNECTION=sync
```

### 4. Application Setup
Link storage, run database migrations, and seed data.

```bash
php artisan storage:link

php artisan migrate --force

php artisan db:seed --force
```

### 5. Optimization
Cache configuration and routes for better performance.

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Set Permissions
**Important:** Run this step *last* to ensure the web server owns all files created during the build process.

```bash
sudo chown -R www-data:www-data /var/www/client-per-klant

sudo chmod -R 775 storage bootstrap/cache
```
