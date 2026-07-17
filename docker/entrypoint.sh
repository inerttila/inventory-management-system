#!/bin/sh
set -e

cd /var/www/html

# Ensure storage directories exist (needed when using a fresh Docker volume)
mkdir -p \
    storage/app/public \
    storage/framework/cache/data \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# Create .env from template if it does not exist
if [ ! -f .env ]; then
    cp .env.docker .env
fi

# Ensure APP_KEY is set
if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force --no-interaction
fi

# Wait for PostgreSQL container
echo "Waiting for PostgreSQL..."
for i in $(seq 1 30); do
    if php -r "
        \$host = getenv('DB_HOST') ?: 'postgres';
        \$port = getenv('DB_PORT') ?: '5432';
        \$db = getenv('DB_DATABASE') ?: 'inventory';
        \$user = getenv('DB_USERNAME') ?: 'inventory';
        \$pass = getenv('DB_PASSWORD') ?: 'secret';
        new PDO(\"pgsql:host=\$host;port=\$port;dbname=\$db\", \$user, \$pass);
    " 2>/dev/null; then
        echo "PostgreSQL is ready."
        break
    fi
    sleep 2
done

# Laravel setup
php artisan package:discover --ansi --no-interaction
php artisan config:clear --no-interaction
php artisan storage:link --force --no-interaction

if [ ! -f storage/.docker-initialized ]; then
    echo "Running database migrations and seeders..."
    php artisan migrate --force --seed --no-interaction
    touch storage/.docker-initialized
else
    php artisan migrate --force --no-interaction
fi

chown -R www-data:www-data storage bootstrap/cache

echo "Application is ready at ${APP_URL:-http://localhost:8080}"

exec /usr/bin/supervisord -c /etc/supervisord.conf
