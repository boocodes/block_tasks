<?php

class OrderContainer
{
    private array $singletones;
    private array $factories;

    public function set(string $id, callable $factory): void
    {
        $this->factories[$id] = $factory;
    }
    public function has(string $id): bool
    {
        return isset($this->factories[$id]) || isset($this->singletones[$id]);
    }
    public function singleton(string $id, callable $factory): void
    {
        $this->factories[$id] = function() use ($id, $factory)
        {
            if(!isset($this->singletones[$id]))
            {
                $this->singletones[$id] = $factory($this);
            }
            return $this->singletones[$id];
        };
    }

    public function get(string $id)
    {
        if(!isset($this->factories[$id]))
        {
            throw new BadMethodCallException($id . ' not exist' . '\n');
        }
        return $this->factories[$id]($this);
    }

}