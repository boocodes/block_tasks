<?php


namespace Domain\Container;

use Domain\ValueObject\OrderElem;
use http\Exception\BadMethodCallException;
use ReflectionClass;
use ReflectionException;

class OrderContainer
{
    private array $singletones = [];
    private array $factories = [];

    public function set(string $name, callable $factory): void
    {
        $this->factories[$name] = $factory;
    }
    public function has(string $name): bool
    {
        return isset($this->factories[$name]) || isset($this->singletones[$name]);
    }
    public function singleton(string $name, callable $factory): void
    {
        $this->factories[$name] = function () use ($name, $factory) {
            if(!isset($this->singletones[$name])) {
                $this->singletones[$name] = $factory();
            }
            return $this->singletones[$name];
        };
    }
    public function get(string $name)
    {
        if(isset($this->factories[$name])) {
            return $this->factories[$name]($this);
        }
        try{
            if(class_exists($name)) {
                return $this->prepareObject($name);
            }
        }
        catch (ReflectionException $e){
            echo $e->getMessage();
        }
        throw  new BadMethodCallException($name . ' does not exist');
    }

    private function prepareObject(string $name)
    {
        $classReflector = new ReflectionClass($name);
        if($classReflector->isAbstract() || $classReflector->isInterface()) {
            throw  new ReflectionException('Cannot create an interface or abstract class');
        }
        $constructorReflector = $classReflector->getConstructor();
        if(!$constructorReflector)
        {
            return new $name();
        }
        $constructReflectorArgs = $constructorReflector->getParameters();
        if(empty($constructReflectorArgs)) {
            return new $name();
        }
        $args = [];
        foreach ($constructReflectorArgs as $constructReflectorArg) {
            $argType = $constructReflectorArg->getType();
            if($argType)
            {
                $argTypeName = $argType->getName();
                $args[] = $this->get($argTypeName);
            }
        }
        unset($constructReflectorArg);
        return new $name(...$args);
    }
}