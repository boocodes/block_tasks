<?php


namespace Task1;
class OrderFile
{
    private string $storageDir;

    public function __construct()
    {
        $this->storageDir = __DIR__ . '/../order.json';
    }
    public function setStorageDir(string $storageDir): void
    {
        $this->storageDir = $storageDir;
    }
    public function saveOrder(array $order): array
    {
        $this->ensureStorageDir();
        $existing = [];
        $now = new \DateTimeImmutable();
        $order['createdAt'] = $now->format('c');
        if (file_exists($this->storageDir)) {
            $raw = file_get_contents($this->storageDir);
            $existing = json_decode($raw, true);
            if (!is_array($existing)) {
                $existing = [];
            }
        }
        $existing[] = $order;
        file_put_contents($this->storageDir, json_encode($existing, JSON_PRETTY_PRINT));
        return [
            'status' => 'ok',
            'message' => $order,
        ];
    }
    private function ensureStorageDir(): void
    {
        $dir = dirname($this->storageDir);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
    }
}