#!/bin/bash

# Load .env variables
DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f2)
DB_USER=$(grep DB_USERNAME .env | cut -d '=' -f2)
DB_PASS=$(grep DB_PASSWORD .env | cut -d '=' -f2)
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2)

echo "Creating database if it doesn't exist..."

mysql -u"$DB_USER" -p"$DB_PASS" -h "$DB_HOST" -e "CREATE DATABASE IF NOT EXISTS \`$DB_NAME\`;"

if [ $? -eq 0 ]; then
    echo "Database '$DB_NAME' is ready."
else
    echo "Failed to create database."
    exit 1
fi

read -p "Are you sure you want to alter the database? (yes/no): " confirm
if [ "$confirm" == "yes" ]; then
    echo "Running migrations and seeders..."
	
    php artisan migrate --force
    if [ $? -ne 0 ]; then
        echo "Migration failed. Dropping database '$DB_NAME'..."
        mysql -u"$DB_USER" -p"$DB_PASS" -h "$DB_HOST" -e "DROP DATABASE IF EXISTS \`$DB_NAME\`;"
        exit 1
    fi

    php artisan db:seed --force
    if [ $? -ne 0 ]; then
        echo "Seeding failed. Dropping database '$DB_NAME'..."
        mysql -u"$DB_USER" -p"$DB_PASS" -h "$DB_HOST" -e "DROP DATABASE IF EXISTS \`$DB_NAME\`;"
        exit 1
    fi
	
    echo "Migrations and seed completed."
else
    echo "Operation aborted."
fi

echo "Setup completed!"
