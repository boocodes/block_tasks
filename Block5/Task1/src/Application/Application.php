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
        foreach ($this->argv as $argv){
            switch ($argv){
                case '--help':
                    $this->migrationService->getHelpInfoCLI();
                    break;
                case 'migrate':
                    $this->migrationService->migrate();
                    break;
                case 'migrate:rollback':
                    $this->migrationService->migrateRollback();
                    break;
                case 'migrate:specified':
                    $this->migrationService->migrateSpecific(CLHelper::get("Input migration filename: "));
                    break;
                case 'migrate:list':
                    $this->migrationService->getMigrateListAndDisplay();
                    break;
                case '--seed':
                    var_dump("with seeds");
                    break;
                    case '--help':

            }
        }
    }

}