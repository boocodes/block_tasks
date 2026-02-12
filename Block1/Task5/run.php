<?php

$start = memory_get_usage();


$ar = range(1, 1_000_000);

echo ((memory_get_usage() - $start ) )  ;