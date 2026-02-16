<?php

namespace Legacy;

use DateTimeImmutable;

class OrderService
{
    private string|array $responseValue;
    private bool $responseStatus = false;
    private float $tax = 0.05;
    private array $order = [];
    private string $storageFile;
    private bool $debug;
    private string $adminEmail;


    public function __construct()
    {
        $this->storageFile = __DIR__ . '/../../var/orders.json';
        $this->adminEmail = 'admin@example.com';
        $this->debug = true;
    }

    /**
     * Создаёт заказ, вызывает внутренние функции валидации, расчета цены и скидки, сохранения и отправки уведомления,
     * возвращает массив "заказа".
     *
     * @param array $input Ожидается структура:
     * [
     *   'customer' => ['email' => '...', 'name' => '...'],
     *   'items' => [
     *      ['sku' => 'A1', 'title' => 'Item', 'price' => 199.99, 'qty' => 2],
     *   ],
     *   'payment' => ['method' => 'card|cash|invoice', 'cardNumber' => '....' (опц.)],
     *   'delivery' => ['type' => 'courier|pickup|post', 'address' => '...' (для courier/post)],
     *   'promoCode' => 'WELCOME10|VIP|FREESHIP' (опц.),
     * ]
     */
    public function createOrder(array $input): array
    {
        $validator = new OrderValidator();
        if(!$validator->validate($input)) {
            return ['ok'=>$validator->getResponseStatus(), 'error'=>$this->responseValue];
        }
        $this->pricingCalculate($input);
        $this->discountCalculate($input);
        $this->paymentCalculate($input);
        $this->ensureStorageDir();
        $this->savingOrder();
        var_dump($this->order);
        return $this->order;
    }

    /**
     * Рассчитывает цену без учета скидки
     *
     * @param array $input Ожидается структура:
     * [
     * 'customer' => ['email' => '...', 'name' => '...'],
     * 'items' => [
     * ['sku' => 'A1', 'title' => 'Item', 'price' => 199.99, 'qty' => 2],
     * ],
     * 'payment' => ['method' => 'card|cash|invoice', 'cardNumber' => '....' (опц.)],
     * 'delivery' => ['type' => 'courier|pickup|post', 'address' => '...' (для courier/post)],
     * 'promoCode' => 'WELCOME10|VIP|FREESHIP' (опц.),
     * ]
     */
    private function pricingCalculate(array &$input): void
    {
        $this->order['id'] = (string)time() . '-' . rand(1000, 9999);
        $this->order['customer'] = [
            'email' => trim((string)$input['customer']['email']),
            'name' => (string)($input['customer']['name'] ?? ''),
        ];
        $items = $input['items'];
        $subtotal = 0;

        foreach ($items as &$it) {
            $it['sku'] = isset($it['sku']) ? (string)$it['sku'] : '';
            $it['title'] = isset($it['title']) ? (string)$it['title'] : 'Unknown';
            $it['qty'] = isset($it['qty']) ? (int)$it['qty'] : 1;
            $it['price'] = isset($it['price']) ? (float)$it['price'] : 0.0;

            if ($it['qty'] < 1) $it['qty'] = 1;
            if ($it['price'] < 0) $it['price'] = 0;

            $subtotal += $it['price'] * $it['qty'];
        }
        unset($it);
        $this->order['items'] = $items;


        $delivery = $input['delivery'] ?? [];
        $deliveryType = isset($delivery['type']) ? (string)$delivery['type'] : 'courier';

        $deliveryCost = 0;

        if ($deliveryType === 'courier') {
            $deliveryCost = ($subtotal >= 1000) ? 0 : 199;
            if (empty($delivery['address'])) {
                if ($this->debug) {
                    error_log("[DEBUG] courier without address for order {$this->order['id']}");
                }
            }
        } elseif ($deliveryType === 'pickup') {
            $deliveryCost = 0;
        } elseif ($deliveryType === 'post') {
            $deliveryCost = 299;
        } else {
            $deliveryType = 'courier';
            $deliveryCost = 199;
        }

        $this->order['delivery'] = [
            'type' => $deliveryType,
            'cost' => $deliveryCost,
            'address' => (string)($delivery['address'] ?? ''),
        ];
        $this->order['pricing']['subtotal'] = $subtotal;
    }

    private function discountCalculate(&$input): void
    {
        $discount = 0;
        $subtotal = $this->order['pricing']['subtotal'];
        $promoCode = isset($input['promoCode']) ? strtoupper(trim((string)$input['promoCode'])) : '';
        if ($promoCode !== '') {
            if ($promoCode === 'WELCOME10') {
                $discount = $subtotal * 0.10;
            } elseif ($promoCode === 'VIP') {
                if ($subtotal >= 2000) {
                    $discount = 300;
                } else {
                    $discount = 100;
                }
            } elseif ($promoCode === 'FREESHIP') {
                $deliveryCost = 0;
            }
        }
        $tax = ($subtotal - $discount) * $this->tax;
        $total = ($subtotal - $discount) + $this->tax + $this->order['delivery']['cost'];
        if ($total < 0) $total = 0;
        $this->order['pricing']['total'] = $total;
        $this->order['pricing']['discount'] = $discount;
        $this->order['pricing']['tax'] = $tax;
        $this->order['pricing']['promoCode'] = $promoCode;
    }

    private function paymentCalculate(&$input): void
    {
        $payment = $input['payment'] ?? [];
        $paymentMethod = isset($payment['method']) ? (string)$payment['method'] : 'card';
        $cardNumber = 0;
        if ($paymentMethod === 'card') {
            $paymentStatus = 'paid';
        } elseif ($paymentMethod === 'cash') {
            $paymentStatus = 'cash_on_delivery';

        } elseif ($paymentMethod === 'invoice') {
            $paymentStatus = 'awaiting_invoice';
        } else {
            $paymentMethod = 'card';
            $paymentStatus = 'pending';
        }
        $this->order['payment'] = [
            'method' => $paymentMethod,
            'status' => $paymentStatus,
        ];
    }


    private function savingOrder(): void
    {
        $existing = [];
        $now = new DateTimeImmutable();
        $this->order['createdAt'] = $now->format('c');
        if (file_exists($this->storageFile)) {
            $raw = file_get_contents($this->storageFile);
            $existing = json_decode($raw, true);
            if (!is_array($existing)) {
                $existing = [];
            }
        }
        $existing[] = $this->order;
        file_put_contents($this->storageFile, json_encode($existing, JSON_PRETTY_PRINT));
        $this->responseStatus = true;
        $this->responseValue = $this->order;
        $notifier = new Notifier($this->order['customer']['email'], $this->adminEmail, true);
        $customerMessage = 'Thanks! Your order ' . $this->order['id'] . ' total=' . $this->order['pricing']['total'] . PHP_EOL;
        $adminMessage = 'New order ' . $this->order['id'] . ' total=' . $this->order['pricing']['total'] . ' customer=' . $this->order['customer']['email'] . PHP_EOL;
        $notifier->notifyCustomer($customerMessage);
        $notifier->notifyAdmin($adminMessage);

    }



    private function ensureStorageDir(): void
    {
        $dir = dirname($this->storageFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}