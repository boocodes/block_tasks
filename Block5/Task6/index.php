<?php

include_once 'vendor/autoload.php';

use StorageTask6\Application\Application;
use StorageTask6\Application\Database\Services\ConnectionService;
use StorageTask6\Application\Database\Services\MigrationService;
use StorageTask6\Application\Utils\CLHelper;
use StorageTask6\Application\Database\Services\SeedService;
use Database6\seeders\DatabaseSeeds;
use StorageTask6\Application\ORM\ORM;
use function Database6\bin\race_test;


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
//var_dump($query1);



$offsetQuery1 = $orm->offsetPagination('offset_select.sql', 25591, 10, 1);
$offsetQuery2 = $orm->offsetPagination('offset_select.sql', 25591, 10, 500);
$offsetQuery3 = $orm->offsetPagination('offset_select.sql', 25591, 10, 2000);

var_dump($offsetQuery1);

exit(0);
$keysetQuery1 = $orm->keysetPagination('keyset_select.sql', 25591, 10);
$resultData = $keysetQuery1;
var_dump($resultData['data']);
while(!empty($resultData) && count($resultData['data']) > 0 && $resultData['lastId'] !== null) {
    $nextKeysetQuery = $orm->keysetPagination('keyset_select.sql', 25591, 10, $resultData['lastId']);
    $resultData = $nextKeysetQuery;
}



//race_test($connectionService->getDatabase(), 3, 10);


// php index.php --help for detail info
