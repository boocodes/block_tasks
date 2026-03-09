<?php

include_once 'vendor/autoload.php';

use StorageTask5\Application\Application;
use StorageTask5\Application\Database\Services\ConnectionService;
use StorageTask5\Application\Database\Services\MigrationService;
use StorageTask5\Application\Utils\CLHelper;
use StorageTask5\Application\Database\Services\SeedService;
use Database5\seeders\DatabaseSeeds;
use StorageTask5\Application\ORM\ORM;
use function Database5\bin\race_test;


$databaseInfo = require_once 'config.php';


$connectionService = new ConnectionService($databaseInfo['DATABASE_HOST'], $databaseInfo['DATABASE_USER'], $databaseInfo['DATABASE_PASSWORD'], $databaseInfo['DATABASE_NAME']);
$migrationService = new MigrationService($connectionService->getConnection());
$seedingService = new SeedService(new DatabaseSeeds(), $connectionService->getConnection());


$application = new Application($connectionService, $migrationService, $seedingService);
//$application->boot();

$orm = new ORM($connectionService->getConnection());

$query1 = $orm->selectQuery('q1_top_users.sql');
$query2 = $orm->selectQuery('q2_products_sales.sql', [':date_from' => '2021-09-16 13:00:00', ':date_to' => '2025-09-16 18:00:00']);
$query3 = $orm->selectQuery('q3_orders_with_items.sql');
$query4 = $orm->selectQuery('q4_conversion.sql', [':date_from' => '2021-09-16 13:00:00', ':date_to' => '2025-09-16 18:00:00']);
$query5 = $orm->selectQuery('q5_suspicious.sql');
//var_dump($query5);


race_test($connectionService->getDatabase(), 3, 10);


// php index.php --help for detail info
