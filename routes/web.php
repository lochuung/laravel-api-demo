<?php

use Illuminate\Support\Facades\Route;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Dashboard Route
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

// Users CRUD Routes
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', function () {
        return view('users.index');
    })->name('index');
    
    Route::get('/create', function () {
        return view('users.create');
    })->name('create');
    
    Route::get('/{id}', function ($id) {
        return view('users.show');
    })->name('show');
    
    Route::get('/{id}/edit', function ($id) {
        return view('users.edit');
    })->name('edit');
});

// Products CRUD Routes
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', function () {
        return view('products.index');
    })->name('index');
    
    Route::get('/create', function () {
        return view('products.create');
    })->name('create');
    
    Route::get('/{id}', function ($id) {
        return view('products.show');
    })->name('show');
    
    Route::get('/{id}/edit', function ($id) {
        return view('products.edit');
    })->name('edit');
});

// Orders CRUD Routes
Route::prefix('orders')->name('orders.')->group(function () {
    Route::get('/', function () {
        return view('orders.index');
    })->name('index');
    
    Route::get('/create', function () {
        return view('orders.create');
    })->name('create');
    
    Route::get('/{id}', function ($id) {
        return view('orders.show');
    })->name('show');
    
    Route::get('/{id}/edit', function ($id) {
        return view('orders.edit');
    })->name('edit');
});
