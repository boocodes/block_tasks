<?php
//gc_disable();

class Node
{
    public ?Node $ref = null;
    public function __construct(?Node $ref = null)
    {
        $this->ref = $ref;
    }
}

$start_time = microtime(true);

for ($i = 0; $i < 50_000; ++$i) {
    $a = new Node();
    $b = new Node();

    $a->ref = $b;
    $b->ref = $a;

    if($i % 1000 == 0 && $i !== 0)
    {
        echo memory_get_usage(true) / 1024 . "\n";
    }

}

$end_time = microtime(true);
echo round(($end_time - $start_time) * 1000, 5) . "\n";