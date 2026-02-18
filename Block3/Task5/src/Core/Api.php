<?php

namespace Task5\Core;


use DateTime;
use Task5\Core\Routes;
use Task5\Core\Sender;
use Task5\Infrastructure\TaskRepository;
use Task5\Application\DTO\Task;
use Task5\Domain\Enums\StatusEnum;

Routes::post('/tasks', function (Request $request) {
    $request->validate(['name' => 'required']);

    //Task::getAll();
    Task::create($request->getInputData()['name']);

});


Routes::get('/tasks', function (Request $request) {

});

Routes::get('/task/{$id}', function (Request $request, $id) {

});

Routes::delete('/task/{$id}', function (Request $request, $id) {

});

Routes::patch('/task/{$id}', function (Request $request, $id) {

});

Routes::pageNotFound(function (Request $request) {

});