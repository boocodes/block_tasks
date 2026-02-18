<?php
//namespace Task5\Infrastructure;
//require_once 'vendor/autoload.php';
//use Task5\Core\App;
//
//$config = require_once "./src/config.php";
//
//App::run($config);


namespace Task5;

use Task5\Application\DTO\Task;
use Task5\Domain\Enums\StatusEnum;

require 'vendor/autoload.php';

try {
    //$result = Task::create(['title' => 'e', 'description' => 'about', 'id' => '99', 'lalal' => 'lalal', 'status' => 'new']);
    //$result2 = Task::get(311);
    //$result3 = Task::update(['id'=>'99', 'description'=>'tttt', 'heloier' => 'mir']);
    //Task::delete("990");
    $result4 = Task::getAll();
    var_dump($result4);
} catch (\Exception $e) {
    echo $e->getMessage();
}