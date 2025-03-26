<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Schema\Schema;
use App\Services\DBManager;
use App\Services\DoctrineAuthService;

class DoctrineServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('doctrine.connection', function ($app) {
            $config = config('database.connections.doctrine');
            
            $connectionParams = [
                'driver' => $config['driver'],
                'path' => $config['path'],
            ];
            
            return DriverManager::getConnection($connectionParams);
        });
        
        $this->app->singleton('doctrine.schema', function ($app) {
            return new Schema();
        });
        
        $this->app->singleton(DBManager::class, function ($app) {
            return new DBManager(
                $app->make('doctrine.connection'),
                $app->make('doctrine.schema')
            );
        });
        
        $this->app->singleton(DoctrineAuthService::class, function ($app) {
            return new DoctrineAuthService(
                $app->make('doctrine.connection')
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Create the database file if it doesn't exist
        $dbPath = config('database.connections.doctrine.path');
        if (!file_exists($dbPath)) {
            touch($dbPath);
            chmod($dbPath, 0666);
        }
    }
}