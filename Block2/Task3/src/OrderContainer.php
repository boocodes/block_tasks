<?php

namespace Task3;
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
        $this->factories[$id] = function () use ($id, $factory) {
            if (!isset($this->singletones[$id])) {
                $this->singletones[$id] = $factory($this);
            }
            return $this->singletones[$id];
        };
    }

    public function get(string $id)
    {
        if (isset($this->factories[$id])) {
            return $this->factories[$id]($this);
        }
        try {
            if (class_exists($id)) {
                return $this->prepareObject($id);
            }
        } catch (ReflectionException $e) {
            echo $e->getMessage();
        }
        throw new BadMethodCallException($id . ' does not exist.' . '\n');
    }

    /**
     * @throws ReflectionException
     */
    private function prepareObject(string $class)
    {
        $classReflector = new \ReflectionClass($class);
        if ($classReflector->isInterface() || $classReflector->isAbstract()) {
            throw new ReflectionException('Cannot create an interface or abstract class ' . $class . '.');
        }
        $constructorReflector = $classReflector->getConstructor();
        if (!$constructorReflector) {
            return new $class();
        }
        $constructorArgs = $constructorReflector->getParameters();
        if (empty($constructorArgs)) {
            return new $class();
        }
        $args = [];
        foreach ($constructorArgs as $arg) {
            $argType = $arg->getType();
            if ($argType) {
                $argTypeName = $argType->getName();
                $args[] = $this->get($argTypeName);
            }
        }
        unset($arg);
        return new $class(...$args);
    }

}