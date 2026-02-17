<?php


namespace Task5\Core;

class Request
{
    private array $headers;
    private false|string $inputData;
    private string $testData = 'hello from request';

    public function __construct(array $headers, false|string $inputData)
    {
        $this->headers = $headers;
        $this->inputData = $inputData;
    }
    // required | max:(size 1-255)
    public function validate(array $data): bool
    {

    }
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getInputData(): false|string
    {
        return $this->inputData;
    }

    public function getTestData(): string|false
    {
        return $this->testData;
    }
}