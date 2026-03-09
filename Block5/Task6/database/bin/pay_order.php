<?php

namespace Database6\bin;

use Faker\Factory;
use PDO;
use PDOException;
use StorageTask6\Application\Utils\CLHelper;
use StorageTask6\Domain\Enums\TextColorsEnum;
use StorageTask6\Domain\Enums\OrderStatusEnum;
use StorageTask6\Domain\Enums\PaymentsProviderEnum;
use StorageTask6\Domain\Enums\PaymentStatusEmum;


function pay_order(PDO $connection, string $order_id): void
{
    $faker = Factory::create();
    try {
        $connection->beginTransaction();
        $selectQuery = $connection->prepare("SELECT id, status, total_amount FROM orders WHERE id = :order_id FOR UPDATE");

        $selectQuery->execute(['order_id' => $order_id]);
        $order = $selectQuery->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            CLHelper::send("Order " . $order_id . " not found", TextColorsEnum::RED);
            return;
        }
        if ($order['status'] !== OrderStatusEnum::NEW->value) {
            CLHelper::send("Order " . $order_id . " is not new", TextColorsEnum::RED);
            return;
        }
        $insertQuery = $connection->prepare("INSERT INTO payments (order_id, provider, created_at, status) VALUES (:order_id, :provider, NOW(), :status)");
        $insertQuery->execute(['order_id' => $order_id, 'provider' => PaymentsProviderEnum::PAYPAL->value, 'status' => PaymentStatusEmum::PAID->value]);

        $updateQuery = $connection->prepare("UPDATE orders SET status = 'paid' WHERE id = :order_id");
        $updateQuery->execute(['order_id' => $order_id]);

        $auditLogQuery = $connection->prepare("INSERT INTO audit_log (entity_type, entity_id, action, meta, created_at) VALUES (:entity_type, :entity_id, :action, :meta, NOW())");
        $auditLogQuery->execute(
            [
                'entity_type' => substr($faker->word(), 0, 20),
                'entity_id' => $faker->randomDigit(),
                'action' => substr($faker->word(), 0, 255),
                'meta' => json_encode(['name' => $faker->name(), 'age' => random_int(18, 44)])
            ]
        );
        $connection->commit();
        CLHelper::send("Order " . $order_id . " paid", TextColorsEnum::GREEN);
    } catch (PDOException $e) {
        CLHelper::send($e->getMessage(), TextColorsEnum::RED);
        $connection->rollBack();
    }
}