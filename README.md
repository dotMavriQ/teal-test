# TEAL

TEAL is a Laravel-based application for tracking and managing reading collections. The application provides tools for importing data from Goodreads, organizing books, and tracking reading progress.

## Core Features

- Book collection management with CRUD operations
- Import functionality for Goodreads data
- Custom cover image support
- Reading status tracking
- Clean URL structure with slugs
- Dark-themed UI based on Gruvbox color palette
- Responsive design for desktop and mobile

## Technology

- Laravel 10
- Bootstrap 5 with custom theming
- PostgreSQL database
- Eloquent ORM
- Laravel's built-in authentication

## Installation

### Prerequisites
1. PHP 8.1+ with required extensions
2. Composer
3. PostgreSQL database server
4. Node.js and npm for frontend assets

### Installation Steps
1. Clone the repository
2. Configure PostgreSQL:
   ```bash
   # Create a database for TEAL
   createdb teal
   # Or use pgAdmin or another PostgreSQL admin tool
   ```
3. Run the installation script: `./install.sh`
4. Start the server with `php artisan serve`

The installation script will:
- Create the necessary .env file
- Connect to PostgreSQL and create the database if it doesn't exist
- Run migrations and seed the database
- Create a storage link for file uploads
- Migrate any existing file-based data to the database

### Database Configuration
The default database configuration uses:
- Database: `teal`
- Username: `postgres`
- Password: `postgres`

You can change these in your `.env` file after installation.

## Usage

### Importing Books

1. Export your Goodreads library as CSV
2. Convert to JSON format
3. Use the Import Books page to upload the JSON file

### Default Login

After installation, you can log in with:
- Email: dotmavriq@dotmavriq.life
- Password: TEALAdmin@2025#Secure

## Testing

The application includes comprehensive tests for all features. To run the tests:

```bash
./run_tests.sh
```

This will:
1. Create a dedicated PostgreSQL test database (teal_testing)
2. Run all tests against the test database
3. Generate a coverage report

The test suite includes:
- Unit tests for model methods
- Feature tests for authentication
- Feature tests for book operations
- Tests for data migration from JSON to database

## License

This application is licensed under the MIT license.