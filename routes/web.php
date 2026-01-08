<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestMailController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-mail', [TestMailController::class, 'sendTestEmail']);