<?php

namespace App\Console\Commands;

use App\Repositories\UserRepository;
use Illuminate\Console\Command;

class SetupRepositoriesCommand extends Command
{
    protected $signature = 'setup:repositories';
    protected $description = 'Setup TEAL repositories';

    public function handle()
    {
        $this->info('Setting up TEAL repositories...');
        
        try {
            $userRepo = new UserRepository();
            $result = $userRepo->createAdmin();
            
            $status = $result ? 'Created' : 'Already exists';
            $this->line("{$status}: Admin user");
            
            $this->info('Repository setup complete!');
            $this->info('Admin user credentials:');
            $this->info('Email: dotmavriq@dotmavriq.life');
            $this->info('Password: TEALAdmin@2025#Secure');
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Error setting up repositories: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}