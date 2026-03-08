<?php


namespace StorageTask2\Domain\Interfaces;


interface Seedable
{
    public function run(): array;
}