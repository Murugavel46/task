<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('throttle:30,1','guest')->group(function () {
    Route::view('register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.s');
    Route::view('login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('loginsubmit');
});

Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change_password.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change_password');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::get('/welcome', function () {
    return view('auth.welcome');
})->middleware('auth')->name('welcome');
