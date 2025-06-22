#!/bin/bash

# Load .env variables safely
DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f2 | tr -d '"')
DB_USER=$(grep DB_USERNAME .env | cut -d '=' -f2 | tr -d '"')
DB_PASS=$(grep DB_PASSWORD .env | cut -d '=' -f2 | tr -d '"')
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2 | tr -d '"')

echo "Running migrations and seeders..."

php artisan migrate --force
php artisan db:seed --force
composer require predis/predis

echo "✅ Migrations and seeders completed successfully."
echo "✅ Setup completed!"
