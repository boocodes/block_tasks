<?php
namespace Task5;


class OrderFile
{
    private string $filePath;

    public function __construct()
    {
        $this->filePath = __DIR__ . '/../result.json';
    }
    public function setFilePath(string $filePath): void
    {
        $this->filePath = $filePath;
    }
    public function save(array $data): void
    {
        $data = [
            'users' => $data['users'],
            'orders' => $data['orders'],
        ];

        file_put_contents(
            $this->filePath,
            json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }
}