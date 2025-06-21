#!/bin/bash

# Load .env variables safely
DB_NAME=$(grep DB_DATABASE .env | cut -d '=' -f2 | tr -d '"')
DB_USER=$(grep DB_USERNAME .env | cut -d '=' -f2 | tr -d '"')
DB_PASS=$(grep DB_PASSWORD .env | cut -d '=' -f2 | tr -d '"')
DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2 | tr -d '"')

echo "Running migrations and seeders..."

php artisan migrate --force
if [ $? -ne 0 ]; then
    echo "❌ Migration failed. Dropping database '$DB_NAME'..."
    mysql -u"$DB_USER" -p"$DB_PASS" -h "$DB_HOST" -e "DROP DATABASE IF EXISTS \`$DB_NAME\`;"
    exit 1
fi

php artisan db:seed --force
if [ $? -ne 0 ]; then
    echo "❌ Seeding failed. Dropping database '$DB_NAME'..."
    mysql -u"$DB_USER" -p"$DB_PASS" -h "$DB_HOST" -e "DROP DATABASE IF EXISTS \`$DB_NAME\`;"
    exit 1
fi

echo "✅ Migrations and seeders completed successfully."
echo "✅ Setup completed!"
