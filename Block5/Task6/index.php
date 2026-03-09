<?php

include_once 'vendor/autoload.php';

use StorageTask6\Application\Application;
use StorageTask6\Application\Database\Services\ConnectionService;
use StorageTask6\Application\Database\Services\MigrationService;
use StorageTask6\Application\Utils\CLHelper;
use StorageTask6\Application\Database\Services\SeedService;
use Database6\seeders\DatabaseSeeds;
use StorageTask6\Domain\Enums\TextColorsEnum;
use StorageTask6\Application\ORM\ORM;
use function Database6\bin\race_test;


$databaseInfo = require_once 'config.php';


$connectionService = new ConnectionService($databaseInfo['DATABASE_HOST'], $databaseInfo['DATABASE_USER'], $databaseInfo['DATABASE_PASSWORD'], $databaseInfo['DATABASE_NAME']);
$migrationService = new MigrationService($connectionService->getConnection());
$seedingService = new SeedService(new DatabaseSeeds(), $connectionService->getConnection());


$application = new Application($connectionService, $migrationService, $seedingService);
//$application->boot();

$orm = new ORM($connectionService->getConnection());

//$query1 = $orm->selectQuery('q1_top_users.sql');
//$query2 = $orm->selectQuery('q2_products_sales.sql', [':date_from' => '2021-09-16 13:00:00', ':date_to' => '2025-09-16 18:00:00']);
//$query3 = $orm->selectQuery('q3_orders_with_items.sql');
//$query4 = $orm->selectQuery('q4_conversion.sql', [':date_from' => '2021-09-16 13:00:00', ':date_to' => '2025-09-16 18:00:00']);
//$query5 = $orm->selectQuery('q5_suspicious.sql');
//var_dump($query1);


$userWithMaxOrdersCountQuery = $connectionService->getConnection()->query("SELECT user_id, COUNT(*) as count_orders FROM orders GROUP BY user_id ORDER BY count_orders DESC LIMIT 1");
$userWithMaxOrdersCountValue = $userWithMaxOrdersCountQuery->fetch(PDO::FETCH_ASSOC);
$userId = $userWithMaxOrdersCountValue['user_id'];
$ordersCount = $userWithMaxOrdersCountValue['count_orders'];


$limit = 10;
$totalPage = ceil($ordersCount / $limit);

CLHelper::send("Offset: ", TextColorsEnum::RED);
foreach ([1, 500, 2000] as $page) {
    $start = microtime(true);
    $orm->offsetPagination('offset_select.sql', $userId, $limit, $page);
    $end = microtime(true);
    CLHelper::send("Time: " . ($end - $start) * 1000 . ". Page " . $page, TextColorsEnum::GREEN);
}

$idForKeyset = [];
$keysetLastId = null;

for ($i = 1; $i < 2000; $i++) {
    $result = $orm->keysetPagination('keyset_select.sql', $userId, $limit, $keysetLastId);
    if (empty($result['data'])) break;
    $idForKeyset[$i] = $result['lastId'];
    $keysetLastId = $result['lastId'];
}


CLHelper::send("Keyset: ", TextColorsEnum::RED);
$start = microtime(true);
$orm->keysetPagination('keyset_select.sql', $userId, $limit, null)['lastId'];
$end = microtime(true);
CLHelper::send("Time: " . ($end - $start) * 1000 . ". Page 1", TextColorsEnum::GREEN);



foreach ([499, 1999] as $page) {
    if(!isset($idForKeyset[$page])) continue;
    $start = microtime(true);
    $result = $orm->keysetPagination('keyset_select.sql', $userId, $limit, $idForKeyset[$page]);
    $end = microtime(true);
    CLHelper::send("Time: " . ($end - $start) * 1000 . ". Page " . $page, TextColorsEnum::GREEN);
}



//race_test($connectionService->getDatabase(), 3, 10);


// php index.php --help for detail info
