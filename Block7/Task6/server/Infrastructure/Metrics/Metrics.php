<?php

namespace Task6\Infrastructure\Metrics;

class Metrics
{
    private function __construct()
    {
        
    }
    public static function updateMetrics(string $metricsFile)
    {
        $startTime = $_SERVER['REQUEST_START_TIME'] ?? microtime(true);
        $metrics = [];
        if (file_exists($metricsFile)) {
            $metrics = json_decode(file_get_contents($metricsFile), true);
        } else {
            $metrics = [
                'request_total' => 0,
                'response_total_ms' => 0,
                'avarage_ms' => 0,
            ];
        }
        $responseTimeMs = (microtime(true) - $startTime) * 1000;
        $metrics['request_total'] += 1;
        $metrics['response_total_ms'] += $responseTimeMs;
        $metrics['avg_response_ms'] = round($metrics['response_total_ms'] / $metrics['request_total'], 2);
        $metrics['response_total_ms'] = round($metrics['response_total_ms'], 2);
        file_put_contents($metricsFile, json_encode($metrics, JSON_PRETTY_PRINT));
    }
}
