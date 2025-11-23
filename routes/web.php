<?php

use App\Http\Controllers\Auth\OtpLoginController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/login', [OtpLoginController::class, 'showEmailForm'])
    ->name('login');

Route::post('/login', [OtpLoginController::class, 'sendCode'])
    ->name('auth.send-code');

Route::get('/login/verify', [OtpLoginController::class, 'showVerifyForm'])
    ->name('auth.verify');
Route::post('/login/verify', [OtpLoginController::class, 'verifyCode'])
    ->name('auth.verify-code');

Route::post('/logout', [OtpLoginController::class, 'logout'])
    ->name('auth.logout');
