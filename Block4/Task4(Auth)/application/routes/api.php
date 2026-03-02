<?php
use Illuminate\Support\Facades\Route;
use Task3\App\Http\Controllers\AuthController;
use Task3\App\Http\Controllers\Task\CreateController;
use Task3\App\Http\Controllers\Task\DeleteController;
use Task3\App\Http\Controllers\Task\GetByIdController;
use Task3\App\Http\Controllers\Task\GetController;
use Task3\App\Http\Controllers\Task\UpdateController;







Route::post('/registration', [AuthController::class, 'registration']);
Route::post('/login', [AuthController::class, 'login']);





Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/me', [AuthController::class, 'getMe']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/tasks/{task}', GetByIdController::class)->name('index');
    Route::get('/tasks', GetController::class)->name('index');
    Route::post('/tasks', CreateController::class)->name('index');
    Route::patch('/tasks/{task}', UpdateController::class)->name('index');
    Route::delete('/tasks/{task}', DeleteController::class)->name('index');
});
