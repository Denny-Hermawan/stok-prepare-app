<?php

// File: routes/web.php
use Illuminate\Support\Facades\Route;

// Simple test route
Route::get('/', function () {
    return '<h1>Laravel di Vercel Berhasil!</h1><p>Database: ' . config('database.default') . '</p><p>Environment: ' . config('app.env') . '</p>';
});

// Test route dengan JSON response
Route::get('/test', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Laravel API working!',
        'php_version' => PHP_VERSION,
        'laravel_version' => app()->version(),
        'environment' => config('app.env'),
        'database' => config('database.default'),
        'app_key_set' => config('app.key') ? true : false,
    ]);
});

// Debug database connection
Route::get('/db-test', function () {
    try {
        DB::connection()->getPdo();
        return response()->json([
            'database' => 'connected',
            'connection' => config('database.default'),
            'host' => config('database.connections.pgsql.host'),
        ]);
    } catch (Exception $e) {
        return response()->json([
            'database' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

// Existing routes (jika ada)
// Auth::routes(); // Comment dulu jika ada
