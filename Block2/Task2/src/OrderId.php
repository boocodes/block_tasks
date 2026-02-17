<?php


namespace Task2;
class OrderId
{
    private string $id;

    public function __construct()
    {
        $this->id = uniqid();
    }
    public function getId(): string
    {
        return $this->id;
    }
}