<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    ray('...Hola mundo');
    return view('welcome');
});
