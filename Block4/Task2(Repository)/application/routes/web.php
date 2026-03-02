<?php

use Illuminate\Support\Facades\Route;

Route::get('/test', function () {
    return response('hello world', 200, ['Content-Type' => 'text/html']);
    return view('welcome');
});
