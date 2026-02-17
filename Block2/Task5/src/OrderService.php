<?php

namespace Task5;

use DateTimeImmutable;

class OrderService
{
    private Notifier $notifier;
    private OrderValidate $validate;
    private OrderFile $orderFile;
    public array $config = [];
    public array $orders = [];
    public array $users = [];
    public bool $debug = true;

    public function __construct()
    {
        $this->config = [
            'storage' => __DIR__ . '/../../var/data.json',
            'tax' => 0.05,
            'admin_email' => 'admin@example.com',
        ];

        if (file_exists($this->config['storage'])) {
            $raw = file_get_contents($this->config['storage']);
            $data = json_decode((string)$raw, true);
            if (is_array($data)) {
                $this->orders = $data['orders'] ?? [];
                $this->users = $data['users'] ?? [];
            }
        }
    }

    public function process(array $input, ?OrderValidate $validate, ?Notifier $notifier, ?OrderFile $orderFile): array
    {
        if ($validate !== null) {
            $this->validate = $validate;
            if (!$this->validate->validate($input)) {
                return ['ok' => $validate->getStatusResponse(), 'error' => $validate->getValueResponse()];
            }
        }
        $email = trim((string)$input['email']);
        if (!isset($this->users[$email])) {
            $this->users[$email] = [
                'email' => $email,
                'createdAt' => date('c'),
                'orders' => 0,
            ];
        }

        $orderId = uniqid('ord_', true);
        $items = $input['items'] ?? [];
        $subtotal = 0;

        foreach ($items as $item) {
            $price = (float)($item['price'] ?? 0);
            $qty = (int)($item['qty'] ?? 1);
            if ($qty < 1) $qty = 1;
            $subtotal += $price * $qty;
        }

        $discount = 0;
        if (!empty($input['promo'])) {
            if ($input['promo'] === 'VIP') {
                $discount = 200;
            } elseif ($input['promo'] === 'WELCOME') {
                $discount = $subtotal * 0.1;
            }
        }

        $tax = ($subtotal - $discount) * $this->config['tax'];

        $total = $subtotal - $discount + $tax;
        if ($total < 0) $total = 0;

        $order = [
            'id' => $orderId,
            'email' => $email,
            'items' => $items,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $tax,
            'total' => $total,
            'createdAt' => new DateTimeImmutable()->format('c'),
        ];

        $this->orders[] = $order;
        $this->users[$email]['orders']++;

        if ($orderFile !== null) {
            $this->orderFile = $orderFile;
            $this->orderFile->save(['users' => $this->users, 'orders' => $this->orders]);
        }

        if ($notifier !== null) {
            $this->notifier = $notifier;
            $notifier->notifyAdmin("[ADMIN {$this->config['admin_email']}] New order {$order['id']} total={$order['total']}");
            $notifier->notifyUser("[USER {$order['email']}] Your order {$order['id']} created, total={$order['total']}");
        }
        return [
            'ok' => true,
            'order' => $order,
        ];
    }

}