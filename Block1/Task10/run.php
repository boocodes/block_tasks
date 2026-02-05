<?php

class RangeCollection implements Iterator, Countable
{
    private int $start;
    private int $end;
    private int $currentVal;
    public function __construct(int $start, int $end)
    {
        $this->start = $start;
        $this->end = $end;
        $this->rewind();
    }

    public function current(): mixed
    {
        return $this->currentVal;
    }

    public function next(): void
    {
        $this->currentVal++;
    }

    public function key(): mixed
    {
        return $this->current() - $this->start;
    }

    public function valid(): bool
    {
        return ($this->currentVal <= $this->end);
    }

    public function rewind(): void
    {
        $this->currentVal = $this->start;
    }

    public function count(): int
    {
        return $this->end - $this->start + 1;
    }
}


echo "foreach: \n";
$data = new RangeCollection(1, 20);
foreach ($data as $item)
{
    echo $item . "\n";
}
echo "count method\n";
echo count($data) . "\n";


