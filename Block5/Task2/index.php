<?php

include_once 'vendor/autoload.php';

use StorageTask2\Application\Application;
use StorageTask2\Application\Database\Services\ConnectionService;
use StorageTask2\Application\Database\Services\MigrationService;
use StorageTask2\Application\Utils\CLHelper;
use StorageTask2\Application\Database\Services\SeedService;
use Database2\seeders\DatabaseSeeds;



$connectionService = new ConnectionService('localhost', 'root', '', 'Storage');
$migrationService = new MigrationService($connectionService->getConnection());
$seedingService = new SeedService(new DatabaseSeeds(), $connectionService->getConnection());


$application = new Application($connectionService, $migrationService, $seedingService);
$application->boot();


// php index.php --help for detail info
