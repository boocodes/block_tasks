<?php

namespace Task7\Infrastructure\File;

class FileQueue
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
        $this->initializeFile();
    }
    private function initializeFile(): void
    {
        $directory = dirname($this->filePath);
        if(!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
        if(!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }
    public function push(array $job): void
    {
        $jobs = $this->all();
        $jobs[] = $job;
        $this->save($jobs);
    }
    public function all(): array
    {
        $result = file_get_contents($this->filePath);
        return json_decode($result, true) ?? [];
    }
    public function save(array $jobs): void
    {
        file_put_contents($this->filePath, json_encode($jobs, JSON_PRETTY_PRINT));
    }
}