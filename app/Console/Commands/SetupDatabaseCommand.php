<?php

namespace App\Console\Commands;

use App\Services\DBManager;
use Illuminate\Console\Command;

class SetupDatabaseCommand extends Command
{
    protected $signature = 'db:setup';
    protected $description = 'Setup TEAL database with Doctrine DBAL';

    public function handle(DBManager $dbManager)
    {
        $this->info('Setting up TEAL database...');
        
        try {
            $results = $dbManager->setupDatabase();
            
            foreach ($results as $table => $created) {
                $status = $created ? 'Created' : 'Already exists';
                $this->line("{$status}: {$table}");
            }
            
            $this->info('Database setup complete!');
            $this->info('Admin user credentials:');
            $this->info('Email: dotmavriq@dotmavriq.life');
            $this->info('Password: TEALAdmin@2025#Secure');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error setting up database: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}