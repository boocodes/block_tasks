<?php

namespace Database5\bin;

use PDO;
use PDOException;
use Spatie\Async\Pool;
use StorageTask5\Application\Utils\CLHelper;
use StorageTask5\Domain\Enums\TextColorsEnum;

function race_test(array $database, string $order_id, int $processCount): void
{


    CLHelper::send("Setup " . $processCount . " processes", TextColorsEnum::GREEN);
    $pool = Pool::create();

    for ($i = 0; $i < $processCount; $i++) {
        $pool->add(function () use ($database, $order_id, $i) {
            try {
                $connection = new PDO("mysql:host={$database['DATABASE_HOST']};dbname={$database['DATABASE_NAME']}", $database['DATABASE_USER'], $database['DATABASE_PASSWORD']);
                pay_order($connection, $order_id);
            } catch (PDOException $e) {
                CLHelper::send($e->getMessage(), TextColorsEnum::RED);
            }
        });
    }
    $pool->wait();


}