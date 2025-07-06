#!/bin/bash

# Load .env variables safely
DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USER=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASS=$(grep DB_PASSWORD .env | cut -d '=' -f2)
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2)

echo "Running migrations and seeders..."

php artisan optimize
php artisan config:clear
php artisan migrate --force
php artisan db:seed --force

echo "✅ Migrations and seeders completed successfully."
echo "✅ Setup completed!"
