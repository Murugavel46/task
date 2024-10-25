<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::view('register', 'auth.register')->middleware('guest')->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('guest')->name('register.s');


Route::view('login', 'auth.login')->middleware('guest')->name('login'); 
Route::post('/login', [AuthController::class, 'login'])->middleware('guest')->name('loginsubmit');


Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change_password.form');
Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change_password');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/logout',[AuthController::class,'logout'])->name('logout');
Route::get('/welcome', function () {
    return view('auth.welcome');
})->name('welcome');
