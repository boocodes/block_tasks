<?php


namespace Task5\Core;

class Request
{
    private array $headers;
    private array $inputData;
    private array $inputQuery;

    public function __construct(array $headers, array $inputData, array $inputQuery)
    {
        $this->headers = $headers;
        $this->inputData = $inputData;
        $this->inputQuery = $inputQuery;
    }
    // required (at least)
    /*  Example
     * [
     *   'title' => ['required'],
     *   'description => ['required']
     * ]
     */
    public function validate(array $data): void
    {
        if(empty($data)) return;

        foreach ($data as $name => $value) {
            if(in_array('required', $value)) {
                if(!isset($this->inputData[$name]))
                {
                    throw new \Exception($name . ' required. Error');
                }
            }
        }
    }
    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getInputData(): array
    {
        return $this->inputData;
    }
    public function getInputQuery(): array
    {
        return $this->inputQuery;
    }

    public function getTestData(): string|false
    {
        return $this->testData;
    }
}