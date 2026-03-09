<?php


namespace StorageTask4\Domain\Interfaces;


interface Seedable
{
    public function run(): array;
}