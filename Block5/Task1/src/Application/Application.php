<?php

namespace StorageTask\Application;
use StorageTask\Application\Database\Services\MigrationService;
use StorageTask\Application\Database\Services\ConnectionService;
use StorageTask\Application\Database\Services\Logger;
use PDO;
use StorageTask\Application\Utils\CLHelper;


class Application{
    private ConnectionService $connection;
    private MigrationService $migrationService;

    private array $argv;
    public function __construct(ConnectionService $connection, MigrationService $migrationService)
    {
        $this->connection = $connection;
        $this->migrationService = $migrationService;
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
                    $this->migrationService->migrate();
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