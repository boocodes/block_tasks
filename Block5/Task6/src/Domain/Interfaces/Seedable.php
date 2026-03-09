<?php


namespace StorageTask6\Domain\Interfaces;


interface Seedable
{
    public function run(): array;
}