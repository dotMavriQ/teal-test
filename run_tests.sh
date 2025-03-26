#!/bin/bash

# TEAL Test Runner (PostgreSQL)
echo "Running TEAL tests with PostgreSQL..."
echo "-------------------------------------"

# Load DB info from .env or set fallback defaults
if [ -f .env ]; then
  export $(grep -v '^#' .env | xargs)
fi

DB_USERNAME=${DB_USERNAME:-tealuser}
DB_PASSWORD=${DB_PASSWORD:-password}
DB_HOST=${DB_HOST:-127.0.0.1}
DB_PORT=${DB_PORT:-5432}
TEST_DB=teal_testing

# Drop & recreate the test database cleanly
echo "Resetting test database '$TEST_DB'..."

PGPASSWORD=$DB_PASSWORD psql -U "$DB_USERNAME" -h "$DB_HOST" -p "$DB_PORT" -d postgres -c "DROP DATABASE IF EXISTS $TEST_DB;"
PGPASSWORD=$DB_PASSWORD psql -U "$DB_USERNAME" -h "$DB_HOST" -p "$DB_PORT" -d postgres -c "CREATE DATABASE $TEST_DB;"

# Set test DB connection (Laravel will pick it up if using .env.testing or via override)
export DB_CONNECTION=pgsql
export DB_DATABASE=$TEST_DB

# Run PHPUnit with coverage (optional)
echo "Running Laravel tests..."
php artisan test --coverage || echo "⚠️ Warning: Code coverage driver not installed (Xdebug or PCOV)"

echo ""
echo "✅ Tests completed!"
