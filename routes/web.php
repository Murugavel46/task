<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


Route::get('/', function () {
    return view('welcome');
});


Route::middleware('throttle:300,1', 'guest')->group(function () {
    Route::view('register', 'auth.register')->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.s');
    Route::view('login', 'auth.login')->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('loginsubmit');
});


Route::view('forgetPassword', 'auth.forgetPassword')->name('forgetPasswordForm');
Route::post('/forgetPassword', [AuthController::class, 'forgetPassword'])->name('forgetPassword');


Route::middleware('auth')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('change_password.form');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change_password');
    Route::get('/createBook', [AuthController::class, 'create'])->name('booksCreate');
    Route::post('/booksStore', [AuthController::class, 'store'])->name('booksStore');
    Route::get('/books', [AuthController::class, 'index'])->name('booksIndex');

    Route::get('/index', [AuthController::class, 'index'])->name('dashboard');
    Route::get('/books/{id}/edit', [AuthController::class, 'edit'])->name('booksEdit');
    Route::put('/books/{id}', [AuthController::class, 'update'])->name('booksUpdate');
    Route::delete('/books/{id}', [AuthController::class, 'destroy'])->name('booksDestroy');

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::get('/search', [AuthController::class,'search'])->name('booksSearch');
Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');


Route::get('/welcome', function () {
    return view('auth.welcome');
})->middleware('auth')->name('welcome');
