<?php


namespace StorageTask3\Domain\Interfaces;


interface Seedable
{
    public function run(): array;
}