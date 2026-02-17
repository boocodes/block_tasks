<?php

namespace Task5;

final class ServiceLocator
{
    private array $services = [];
    private array $factories = [];

    public function setObject(string $id, mixed $service): void
    {
        $this->services[$id] = $service;
    }
    public function setFactory(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }

    public function get(string $name): object{
        if(isset($this->services[$name])){
            return $this->services[$name];
        }

        if(isset($this->factories[$name])){
            $this->services[$name] = $this->factories[$name]($this);
            return $this->services[$name];
        }

        throw new \Exception("Service $name not found");
    }

    public function has(string $id): bool
    {
        if(isset($this->services[$id]) || isset($this->factories)) return true;
        return false;
    }

    public function reset(): void
    {
        $this->services = [];
        $this->factories = [];
    }
}