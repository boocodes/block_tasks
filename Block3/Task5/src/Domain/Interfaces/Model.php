<?php


interface Model
{
    public function create(array $data);
    public function update(array $data);
    public function delete(string $id);
    public function patch(array $data);
}