#!/bin/bash

# TEAL PostgreSQL Setup Script
echo "TEAL PostgreSQL Setup"
echo "====================="

# Check if PostgreSQL is installed
if ! command -v psql &> /dev/null; then
    echo "PostgreSQL is not installed. Please install PostgreSQL first."
    echo "  Ubuntu/Debian: sudo apt install postgresql postgresql-contrib"
    echo "  Fedora/RHEL:   sudo dnf install postgresql postgresql-server"
    echo "  macOS:         brew install postgresql"
    exit 1
fi

echo "PostgreSQL is installed."

# Get database credentials from .env or set defaults
if [ -f .env ]; then
    DB_HOST=$(grep DB_HOST .env | cut -d '=' -f2)
    DB_PORT=$(grep DB_PORT .env | cut -d '=' -f2)
    DB_DATABASE=$(grep DB_DATABASE .env | cut -d '=' -f2)
    DB_USERNAME=$(grep DB_USERNAME .env | cut -d '=' -f2)
    DB_PASSWORD=$(grep DB_PASSWORD .env | cut -d '=' -f2)
else
    DB_HOST="127.0.0.1"
    DB_PORT="5432"
    DB_DATABASE="teal"
    DB_USERNAME="tealuser"
    DB_PASSWORD="password"
fi

echo "Will create database '$DB_DATABASE' with user '$DB_USERNAME'"

# Try to connect as the postgres user
echo "Attempting to connect to PostgreSQL..."
if ! sudo -u postgres psql -c '\q' &> /dev/null; then
    echo "Could not connect to PostgreSQL as user 'postgres'."
    echo "Please make sure PostgreSQL is running and the postgres user exists."
    exit 1
fi

echo "Connected to PostgreSQL successfully."

# Create the database user if it doesn't exist
echo "Creating database user '$DB_USERNAME'..."
sudo -u postgres psql -v ON_ERROR_STOP=1 <<EOF
DO \$\$
BEGIN
    IF NOT EXISTS (SELECT FROM pg_catalog.pg_roles WHERE rolname = '$DB_USERNAME') THEN
        CREATE USER $DB_USERNAME WITH ENCRYPTED PASSWORD '$DB_PASSWORD';
    END IF;
END
\$\$;
EOF

# Create the database if it doesn't exist
echo "Creating database '$DB_DATABASE'..."
sudo -u postgres psql -v ON_ERROR_STOP=1 <<EOF
SELECT 'CREATE DATABASE $DB_DATABASE OWNER $DB_USERNAME' WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = '$DB_DATABASE')\gexec
EOF

# Create the test database if it doesn't exist
echo "Creating test database 'teal_testing'..."
sudo -u postgres psql -v ON_ERROR_STOP=1 <<EOF
SELECT 'CREATE DATABASE teal_testing OWNER $DB_USERNAME' WHERE NOT EXISTS (SELECT FROM pg_database WHERE datname = 'teal_testing')\gexec
EOF

# Grant privileges to the user
echo "Granting privileges to user '$DB_USERNAME'..."
sudo -u postgres psql -v ON_ERROR_STOP=1 <<EOF
GRANT ALL PRIVILEGES ON DATABASE $DB_DATABASE TO $DB_USERNAME;
GRANT ALL PRIVILEGES ON DATABASE teal_testing TO $DB_USERNAME;
EOF

echo "PostgreSQL setup complete!"
echo ""
echo "Now you can run the installation script: ./install.sh"