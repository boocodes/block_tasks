<?php

include_once 'vendor/autoload.php';

use StorageTask\Application\Application;
use StorageTask\Application\Database\Services\ConnectionService;
use StorageTask\Application\Database\Services\MigrationService;


$connectionService = new ConnectionService('localhost', 'root', '', 'Storage');
$migrationService = new MigrationService($connectionService->getConnection());

$application = new Application($connectionService, $migrationService);
$application->boot();


// php index.php --help for detail info
