<?php
use Illuminate\Support\Facades\Route;
use Task2\App\Http\Controllers\Task\CreateController;
use Task2\App\Http\Controllers\Task\DeleteController;
use Task2\App\Http\Controllers\Task\GetByIdController;
use Task2\App\Http\Controllers\Task\GetController;
use Task2\App\Http\Controllers\Task\UpdateController;



Route::get('/tasks', GetController::class)->name('index');
Route::get('/tasks/{task}', GetByIdController::class)->name('index');
Route::post('/tasks', CreateController::class)->name('index');
Route::patch('/tasks/{task}', UpdateController::class)->name('index');
Route::delete('/tasks/{task}', DeleteController::class)->name('index');

