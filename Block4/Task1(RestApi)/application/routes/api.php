<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Task\CreateController;
use App\Http\Controllers\Task\DeleteController;
use App\Http\Controllers\Task\GetByIdController;
use App\Http\Controllers\Task\GetController;
use App\Http\Controllers\Task\UpdateController;



Route::get('/tasks', GetController::class)->name('index');
Route::get('/tasks/{task}', GetByIdController::class)->name('index');
Route::post('/tasks', CreateController::class)->name('index');
Route::patch('/tasks/{task}', UpdateController::class)->name('index');
Route::delete('/tasks/{task}', DeleteController::class)->name('index');

