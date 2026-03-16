<?php
use Illuminate\Support\Facades\Route;
use Task7\App\Http\Controllers\AuthController;
use Task7\App\Http\Controllers\Task\CreateController;
use Task7\App\Http\Controllers\Task\DeleteController;
use Task7\App\Http\Controllers\Task\GetByIdController;
use Task7\App\Http\Controllers\Task\GetController;
use Task7\App\Http\Controllers\Task\UpdateController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;



Route::get('/test', function() {
    return response()->json([
        'message' => 'Works',
        'time' => now()
    ]);
});

Route::post('/webhook', function(Request $request)
{
    Log::info('Webhook received', ['data' => $request->all()]);
    file_put_contents(
        storage_path('logs/webhook_received.log'),
        json_encode([
            'occuredAt' => now(),
            'payload' => $request->all(),
            'idempotencyKey' => $request->header('Idempotency-Key')
        ]) . PHP_EOL, FILE_APPEND
    );
    return response()->json(['status'=> 'ok']);
});

Route::post('/test-post', function() {
    return response()->json([
        'message' => 'Works',
        'data' => request()->all()
    ]);
});

Route::post('/registration', [AuthController::class, 'registration']);
Route::post('/login', [AuthController::class, 'login'])->name('login');



Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/me', [AuthController::class, 'getMe']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/tasks/{task}', GetByIdController::class)->name('tasks.show');
    Route::get('/tasks', GetController::class)->name('tasks.index');
    Route::post('/tasks', CreateController::class)->name('tasks.create');
    Route::patch('/tasks/{task}', UpdateController::class)->name('tasks.update');
    Route::delete('/tasks/{task}', DeleteController::class)->name('tasks.delete');
});
