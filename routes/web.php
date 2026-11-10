<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/customers', [CustomerController::class, 'index'])->name('customers');
Route::get('/users', [UserController::class, 'index'])->name('users');
Route::resource('customers', CustomerController::class)->only(['create', 'store', 'edit', 'update']);
Route::resource('users', UserController::class)->only(['create', 'store', 'edit', 'update']);
