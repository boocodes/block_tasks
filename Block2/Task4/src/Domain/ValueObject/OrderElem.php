<?php


namespace Domain\ValueObject;


class OrderElem
{
    private string $name;
    private string $id;
    private float $price;

    public function __construct(string $name, string $id, float $price)
    {
        $this->name = $name;
        $this->id = $id;
        $this->price = $price;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

}