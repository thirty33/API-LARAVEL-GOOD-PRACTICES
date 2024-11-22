<?php

use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->middleware(ThrottleRequests::with(10, 1)) // 10 requests per minute
    ->group(function ()
    {
        include __DIR__ . '/api/v1.php';
    });
