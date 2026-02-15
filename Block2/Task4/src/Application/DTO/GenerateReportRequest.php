<?php


namespace Application\DTO;

class GenerateReportRequest
{
    private array $filters;
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }
    public function getFilters(): array
    {
        return $this->filters;
    }
}