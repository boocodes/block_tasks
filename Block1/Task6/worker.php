<?php


function incrementCounter()
{
    static $counter = 0;
    return ++$counter;
}

// worker simulation

while (true) {
    for ($i = 0; $i <= 30; $i++) {
        echo incrementCounter() . "\n";
        usleep(200);
    }
}


