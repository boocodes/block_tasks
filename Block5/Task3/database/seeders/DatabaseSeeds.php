<?php

namespace Database3\seeders;

use Faker\Factory;
use StorageTask3\Domain\Enums\OrderStatusEnum;
use StorageTask3\Domain\Interfaces\Seedable;
use StorageTask3\Application\Utils\RandomValues;

use StorageTask3\Domain\Enums\PaymentStatusEmum;
use StorageTask3\Domain\Enums\PaymentsProviderEnum;

class DatabaseSeeds implements Seedable
{
    private $faker;

    private array $userEmails = [];
    private array $productsSku = [];
    private static array $ordersId = [];
    private static int $counterOrderId = 0;
    public function run(): array
    {
        $this->faker = Factory::create();
        return [
            'audit_log' =>
                [
                    'count' => 100,
                    'data' =>
                        [
                            'entity_type' => function () {
                                return substr($this->faker->word(), 0, 255);
                            },
                            'entity_id' => function () {
                                return $this->faker->randomDigit();
                            },
                            'action' => function () {
                                return substr($this->faker->word(), 0, 255);
                            },
                            'meta' => function () {
                                return [
                                    'name' => $this->faker->name(),
                                    'age' => random_int(18, 44),
                                ];
                            },
                            'created_at' => function () {
                                return $this->faker->dateTimeBetween('-10 years', 'now');
                            }
                        ]
                ],
            'payments' =>
                [
                    'count' => 100000,
                    'data' => [
                        'order_id' => function () {
                            return self::$ordersId[self::$counterOrderId++];
                        },
                        'status' => function () {
                            $data = [PaymentStatusEmum::PAID->value,
                                PaymentStatusEmum::PAID->value,
                                PaymentStatusEmum::PAID->value,
                                PaymentStatusEmum::FAILED->value,
                                PaymentStatusEmum::PENDING->value
                            ];
                            return $data[array_rand($data)];
                        },

                        'provider' => function () {
                            return RandomValues::getRandomValueFromEnum(PaymentsProviderEnum::class);
                        },
                        'created_at' => function () {
                            return $this->faker->dateTimeBetween('-10 years', 'now');
                        }
                    ],
                ],
            'order_items' =>
                [
                    'count' => 200000,
                    'data' => [
                        'order_id' => [
                            'type' => 'foreign_key',
                            'references' => 'orders'
                        ],
                        'product_id' => [
                            'type' => 'foreign_key',
                            'references' => 'products',
                        ],
                        'qty' => function () {
                            return random_int(1, 9999999);
                        },
                        'price' => function () {
                            return random_int(1, 9999999);
                        }
                    ]
                ],
            'orders' =>
                [
                    'count' => 100000,
                    'data' => [
                        'user_id' => [
                            'type' => 'foreign_key',
                            'references' => 'user'
                        ],
                        'status' => function () {
                            $data = [
                                OrderStatusEnum::PAID->value,
                                OrderStatusEnum::PAID->value,
                                OrderStatusEnum::PAID->value,
                                OrderStatusEnum::NEW->value,
                                OrderStatusEnum::CANCELLED->value
                            ];
                            return $data[array_rand($data)];
                        },
                        'total_amount' => function () {
                            return random_int(10, 99999);
                        },
                        'created_at' => function () {
                            return $this->faker->dateTimeBetween('-10 years', 'now');
                        }
                    ],
                    'hook' => function (int $orderId) {
                        self::$ordersId[] = $orderId;
                    }
                ],
            'products' =>
                [
                    'count' => 20000,
                    'data' => [
                        'sku' => function () {
                            do{
                                $sku = substr($this->faker->uuid(), 0, 100);
                            }
                            while(in_array($sku, $this->productsSku));
                            $this->productsSku[] = $sku;
                            return $sku;
                        },
                        'title' => function () {
                            return substr($this->faker->word(), 0, 30);
                        },
                        'price' => function () {
                            return random_int(10, 99999);
                        },
                        'created_at' => function () {
                            return $this->faker->dateTimeBetween('-10 years', 'now');
                        }
                    ]
                ],
            'user' =>
                [
                    'count' => 50000,
                    'data' => [
                        'email' => function () {
                            do{
                                $newEmail = substr($this->faker->email(), 0, 255);
                            }
                            while(in_array($newEmail, $this->userEmails));

                            $this->userEmails[] = $newEmail;
                            return $newEmail;
                        },
                        'name' => function () {
                            return substr($this->faker->name(), 0, 20);
                        },
                        'created_at' => function () {
                            return $this->faker->dateTimeBetween('-10 years', 'now');
                        }
                    ]
                ]
        ];
    }
}