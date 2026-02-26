<?php

namespace Task7\Infrastructure\WebHook;

use Task7\Domain\Enums\TaskStatus;
use Task7\Infrastructure\File\FileQueue;


class WebHookWorker
{
    private string $webHookUrl;
    private FileQueue $fileQueue;
    private string $logFile;

    public function __construct()
    {
        $config = require __DIR__ . "/../../config.php";
        $this->webHookUrl = $config['WEBHOOK_URL'];
        $this->logFile = __DIR__ . "/../../logs/webhook.log";
        $this->fileQueue = new FileQueue(__DIR__ . "/../../logs/webhook_queue.json");
    }

    public function work(string $taskId, string $status, string $occuredAt): void
    {
        if ($status !== TaskStatus::DONE->value) {
            return;
        }
        $payload = [
            'taskId' => $taskId,
            'status' => $status,
            'occuredAt' => $occuredAt
        ];
        $this->fileQueue->push(
            [
                'payload' => $payload,
                'attempts' => 0,
                'lastAttempt' => null,
            ]
        );
        $this->proccesQueue();
    }
    public function proccesQueue(): void
    {
        $jobs = $this->fileQueue->all();

        $remainingJobs = [];

        foreach ($jobs as $job) {
            if($this->shouldRetry($job)) {
                if($this->sendWebhook($job['payload'])) {
                    $this->displaySuccess($job['payload']);
                    continue;
                }
                $job['attempts']++;
                $job['lastAttempt'] = date('Y-m-d H:i:s');
            }
            if($job['attempts'] < 3) {
                $remainingJobs[] = $job;
            }
            else
            {
                $this->displayError($job['payload'], 'Max attempts');
            }
        }
        $this->fileQueue->save($remainingJobs);
    }
    public function shouldRetry(array $job): bool
    {
        if($job['attempts'] === 0)
        {
            return true;
        }
        $lastAttempt = new \DateTimeImmutable($job['lastAttempt']);
        $now = new \DateTimeImmutable();
        $diff = $now->getTimestamp() - $lastAttempt->getTimestamp();

        return $diff >= 5;
    }
    private function sendWebhook(array $payload): bool
    {
        $curl = curl_init($this->webHookUrl);
        $jsonPayload = json_encode($payload);

        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonPayload);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: ' . strlen($jsonPayload)]);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        return $httpCode >= 200 && $httpCode < 300;
    }

    public function displaySuccess(array $payload): void
    {
        $data = [
            'payload' => $payload,
            'status' => 'success',
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        file_put_contents($this->logFile, json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
    }
    public function displayError(array $payload): void
    {
        $data = [
            'payload' => $payload,
            'status' => 'error',
            'timestamp' => date('Y-m-d H:i:s'),
        ];
        file_put_contents($this->logFile, json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL, FILE_APPEND);
    }
}