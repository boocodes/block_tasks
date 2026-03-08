<?php

namespace StorageTask2\Application;
use StorageTask2\Application\Database\Services\MigrationService;
use StorageTask2\Application\Database\Services\ConnectionService;
use PDO;
use StorageTask2\Application\Database\Services\SeedService;
use StorageTask2\Application\Utils\CLHelper;
use StorageTask2\Domain\Interfaces\Seedable;


class Application{
    private ConnectionService $connection;
    private MigrationService $migrationService;
    private SeedService $seedService;

    private array $argv;
    public function __construct(ConnectionService $connection, MigrationService $migrationService, SeedService $seedService)
    {
        $this->connection = $connection;
        $this->migrationService = $migrationService;
        $this->seedService = $seedService;
        $this->argv = $_SERVER['argv'];
    }
    public function boot(): void{
        if(count($this->argv) <= 1)
        {
            $this->migrationService->getHelpInfoCLI();
        }
        foreach ($this->argv as $argv){
            switch ($argv){
                case '--help':
                    $this->migrationService->getHelpInfoCLI();
                    return;
                case 'migrate':
                    $currentMigrationBatch = $this->migrationService->migrate();
                    foreach ($this->argv as $argvInner){
                        if($argvInner === '--seed'){
                            $this->seedService->seeding($currentMigrationBatch);
                            break;
                        }
                    }
                    return;
                case 'migrate:rollback':
                    $this->migrationService->migrateRollback();
                    return;
                case 'migrate:specified':
                    $this->migrationService->migrateSpecific(CLHelper::get("Input migration filename: "));
                    return;
                case 'migrate:next':
                    $this->migrationService->displayNextBatch();
                    return;
                case 'migrate:list':
                    $this->migrationService->getMigrateListAndDisplay();
                    return;
            }
        }
    }

}