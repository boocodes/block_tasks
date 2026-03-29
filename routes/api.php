<?php


use App\Http\Controllers\TaskController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProjectController;

use Illuminate\Support\Facades\Route;

Route::prefix('projects')->group(function () {
    Route::get('/', [ProjectController::class, 'getAll']);
    Route::get('/{project}', [ProjectController::class, 'get']);
    Route::post('/', [ProjectController::class, 'add']);
    Route::patch('/{project}', [ProjectController::class, 'update']);
    Route::delete('/{project}', [ProjectController::class, 'delete']);
});

Route::prefix('tasks')->group(function () {
    Route::get('', [TaskController::class, 'getAll']);
    Route::get('/{task}', [TaskController::class, 'get']);
    Route::post('/', [TaskController::class, 'add']);
    Route::patch('/{task}', [TaskController::class, 'update']);
    Route::delete('/{task}', [TaskController::class, 'delete']);
});

Route::prefix('comments')->group(function () {
    Route::get('', [CommentController::class, 'getAll']);
    Route::get('/{comment}', [CommentController::class, 'get']);
    Route::post('/', [CommentController::class, 'add']);
    Route::patch('/{comment}', [CommentController::class, 'update']);
    Route::delete('/{comment}', [CommentController::class, 'delete']);
});
