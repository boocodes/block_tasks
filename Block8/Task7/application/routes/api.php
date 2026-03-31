<?php

use Final7\App\Http\Controllers\CommentController;
use Final7\App\Http\Controllers\ProjectController;
use Final7\App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Final7\App\Http\Controllers\AuthController;
use Final7\App\Http\Controllers\WebhookController;
use Final7\App\Models\Metrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Route::prefix('user')->group(function () {
    Route::post('/registration', [AuthController::class, 'registration']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/health', function (Request $request) {
    return response('Alive', 200);
});

Route::get('/metrics', function (Request $request) {
    $metrics = Metrics::query()->orderBy('id')->get();
    
    $averageTime = $metrics->avg('timeDuration');

    return response(['average' => $averageTime, 'total' => $metrics->count()], 200);
});

Route::get('/ready', function (Request $request) {
    try
    {
        $result = DB::select('SELECT 1 as connected');
        return response()->json([
            'status' => 'ready',
            'database' => 'connected',
            'timestamp' => new \DateTimeImmutable()->format('c'),
        ], 200);
    }
    catch(\Exception $exception)
    {
        return response()->json([
            'status' => 'not ready',
            'database' => 'disconnected',
            'timestamp' => new \DateTimeImmutable()->format('c'),
        ], 500);   
        throw $exception;
    }
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
