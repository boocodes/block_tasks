<?php

namespace Legacy;

final class ServiceLocator
{
    private array $services = [];


    public function set(string $id, mixed $service): void
    {
        $this->services[$id] = $service;
    }


    public function getService(string $name): object{
        if(!isset($this->services[$name]))
        {
            return $this->services[$name];
        }
        $classReflector = new \ReflectionClass($this->services[$name]);
        $constructorReflector = $classReflector->getConstructor();
        if(!$constructorReflector)
        {
            return new $name();
        }
        $constructorArgs = $constructorReflector->getParameters();
        if(empty($constructorArgs))
        {
            return new $name();
        }
        $args = [];
        foreach($constructorArgs as $arg)
        {
            $argType = $arg->getType();
            if($argType)
            {
                $argTypeName = $argType->getName();
                $args[] = $this->getService($argTypeName);
            }
        }
        unset($arg);
        return new $name(...$args);
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }

    public function reset(): void
    {
        $this->services = [];
    }
}