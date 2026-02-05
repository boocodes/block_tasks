<?php


function normilizedId(int|string $id): string
{
    if (gettype($id) === 'integer') {
        return (string)$id;
    } else {
        return trim($id);
    }
}

function countAndIterate(Countable&Iterator $obj): int
{
    $count = 0;
    foreach ($obj as $key) {
        $count++;
    }
    return $count;
}

class OnlyCountable implements Countable
{
    private array $items;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function count(): int
    {
        return count($this->items);
    }
}

class Both implements Countable, Iterator
{
    private array $items;
    private int $position = 0;

    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function count(): int
    {
        return count($this->items);
    }

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}

echo "Union with string: ";
echo normilizedId("   41");
echo "\n";

echo "Union with integer: ";
echo normilizedId(42);
echo "\n";


$onlyCountable = new OnlyCountable(['test1', 'test2', 'test3']);
$both = new Both(['test1', 'test2', 'test3']);

echo "Intersection with Countable and Iterator: ";
echo countAndIterate($both);
echo "\n";
echo "Intersection with only Countable: ";
echo countAndIterate($onlyCountable);
echo "\n";