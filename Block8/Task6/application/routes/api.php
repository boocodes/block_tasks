<?php

use Final6\App\Http\Controllers\CommentController;
use Final6\App\Http\Controllers\ProjectController;
use Final6\App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Final6\App\Http\Controllers\AuthController;
use Final6\App\Http\Controllers\WebhookController;

Route::prefix('user')->group(function () {
    Route::post('/registration', [AuthController::class, 'registration']);
    Route::post('/login', [AuthController::class, 'login']);
});


Route::middleware('auth:sanctum')->group(function (): void {

    Route::prefix('webhook')->group(function () {
        Route::get('/', [WebhookController::class, 'getAll']);
        Route::get('/{projectId}', [WebhookController::class, 'get']);
        Route::post('/', [WebhookController::class, 'add']);
        Route::patch('/{webhookId}', [WebhookController::class, 'update']);
        Route::delete('/{webhookId}', [WebhookController::class, 'delete']);
    });

    Route::prefix('user')->group(function () {
        Route::post('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

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
});
