<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;


Route::post('/tasks', [TaskController::class, 'create']);
Route::get('/tasks', [TaskController::class, 'get']);
Route::get('/tasks/{task}', [TaskController::class, 'getById']);
Route::patch('/tasks/{task}', [TaskController::class, 'update']);
Route::delete('/tasks/{task}', [TaskController::class, 'delete']);
