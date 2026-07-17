#!/bin/bash
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

# Initialize MariaDB data directory on first run
if [ ! -d "/var/lib/mysql/mysql" ]; then
    echo "Initializing MariaDB..."
    mariadb-install-db --user=mysql --datadir=/var/lib/mysql > /dev/null
fi

# Start MariaDB temporarily for database setup
echo "Starting MariaDB for setup..."
mysqld --user=mysql --datadir=/var/lib/mysql --skip-networking &
MYSQL_PID=$!

for i in $(seq 1 30); do
    if mariadb-admin ping --silent 2>/dev/null; then
        break
    fi
    sleep 1
done

DB_NAME="${DB_DATABASE:-inventory}"
DB_USER="${DB_USERNAME:-inventory}"
DB_PASS="${DB_PASSWORD:-secret}"

mariadb -e "CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;"
mariadb -e "CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
mariadb -e "GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'localhost';"
mariadb -e "FLUSH PRIVILEGES;"

kill "$MYSQL_PID" 2>/dev/null || true
wait "$MYSQL_PID" 2>/dev/null || true

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

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
