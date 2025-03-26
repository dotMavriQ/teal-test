<?php

namespace App\Services;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Illuminate\Support\Facades\Hash;

class DBManager
{
    protected $connection;
    protected $schema;
    
    public function __construct(Connection $connection, Schema $schema)
    {
        $this->connection = $connection;
        $this->schema = $schema;
    }
    
    public function createTable($name, $callback)
    {
        $schemaManager = $this->connection->createSchemaManager();
        
        if (!$schemaManager->tablesExist([$name])) {
            $schema = new Schema();
            $table = $schema->createTable($name);
            
            $callback($table);
            
            $queries = $schema->toSql($this->connection->getDatabasePlatform());
            
            foreach ($queries as $query) {
                $this->connection->executeStatement($query);
            }
            
            return true;
        }
        
        return false;
    }
    
    public function createUsersTable()
    {
        return $this->createTable('users', function ($table) {
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('name', 'string', ['length' => 255]);
            $table->addColumn('email', 'string', ['length' => 255]);
            $table->addColumn('password', 'string', ['length' => 255]);
            $table->addColumn('remember_token', 'string', ['length' => 255, 'notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => false]);
            $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
            $table->setPrimaryKey(['id']);
            $table->addUniqueIndex(['email']);
        });
    }
    
    public function createAdminUser()
    {
        try {
            $userExists = $this->connection->fetchOne('SELECT COUNT(*) FROM users WHERE email = ?', ['dotmavriq@dotmavriq.life']);
            
            if (!$userExists) {
                $this->connection->insert('users', [
                    'name' => 'Admin',
                    'email' => 'dotmavriq@dotmavriq.life',
                    'password' => Hash::make('TEALAdmin@2025#Secure'),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                
                return true;
            }
        } catch (\Exception $e) {
            // Table might not exist yet
        }
        
        return false;
    }
    
    public function createBooksTable()
    {
        return $this->createTable('books', function ($table) {
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('title', 'string', ['length' => 255]);
            $table->addColumn('author', 'string', ['length' => 255]);
            $table->addColumn('description', 'text', ['notnull' => false]);
            $table->addColumn('isbn', 'string', ['length' => 30, 'notnull' => false]);
            $table->addColumn('cover_image', 'string', ['length' => 255, 'notnull' => false]);
            $table->addColumn('publication_date', 'date', ['notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => false]);
            $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
            $table->setPrimaryKey(['id']);
        });
    }
    
    public function createMoviesTable()
    {
        return $this->createTable('movies', function ($table) {
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('title', 'string', ['length' => 255]);
            $table->addColumn('director', 'string', ['length' => 255]);
            $table->addColumn('description', 'text', ['notnull' => false]);
            $table->addColumn('release_year', 'integer', ['notnull' => false]);
            $table->addColumn('poster_image', 'string', ['length' => 255, 'notnull' => false]);
            $table->addColumn('runtime', 'integer', ['notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => false]);
            $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
            $table->setPrimaryKey(['id']);
        });
    }
    
    public function createGamesTable()
    {
        return $this->createTable('games', function ($table) {
            $table->addColumn('id', 'integer', ['autoincrement' => true]);
            $table->addColumn('title', 'string', ['length' => 255]);
            $table->addColumn('developer', 'string', ['length' => 255]);
            $table->addColumn('description', 'text', ['notnull' => false]);
            $table->addColumn('release_year', 'integer', ['notnull' => false]);
            $table->addColumn('cover_image', 'string', ['length' => 255, 'notnull' => false]);
            $table->addColumn('platform', 'string', ['length' => 255, 'notnull' => false]);
            $table->addColumn('created_at', 'datetime', ['notnull' => false]);
            $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
            $table->setPrimaryKey(['id']);
        });
    }
    
    public function setupDatabase()
    {
        $results = [
            'createUsersTable' => $this->createUsersTable(),
            'createBooksTable' => $this->createBooksTable(),
            'createMoviesTable' => $this->createMoviesTable(),
            'createGamesTable' => $this->createGamesTable(),
        ];
        
        $results['createAdminUser'] = $this->createAdminUser();
        
        return $results;
    }
}