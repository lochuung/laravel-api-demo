<?php

use Illuminate\Support\Facades\Route;

// Auth Routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// Dashboard
Route::view('/', 'dashboard')->name('home');
Route::view('/dashboard', 'dashboard')->name('dashboard');

$viewResource = function (string $prefix, string $viewFolder) {
    Route::prefix($prefix)->name("$prefix.")->group(function () use ($viewFolder) {
        Route::view('/', "$viewFolder.index")->name('index');
        Route::view('/create', "$viewFolder.create")->name('create');
        Route::get('/{id}', fn($id) => view("$viewFolder.show", compact('id')))->name('show');
        Route::get('/{id}/edit', fn($id) => view("$viewFolder.edit", compact('id')))->name('edit');
    });
};

// Resource Routes (no middleware)
$viewResource('users', 'users');
$viewResource('products', 'products');
$viewResource('orders', 'orders');
