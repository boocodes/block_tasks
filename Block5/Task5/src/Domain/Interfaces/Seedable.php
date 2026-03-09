<?php


namespace StorageTask5\Domain\Interfaces;


interface Seedable
{
    public function run(): array;
}