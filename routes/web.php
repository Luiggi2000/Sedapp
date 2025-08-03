<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\EvidenciasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MensajeriaController;
use App\Http\Controllers\DocumentosController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Users Management
    Route::resource('usuarios', UserController::class);
    
    // Roles Management
    Route::resource('roles', RoleController::class);
    
    // Zones Management
    Route::resource('zonas', ZoneController::class);
    
    // Orders Management
    Route::resource('ordenes', OrdenController::class);
    
    // Evidence Management
    Route::resource('evidencias', EvidenciasController::class);
    
    // Messaging System (Microservice)
    Route::get('/mensajeria', [MensajeriaController::class, 'index'])->name('mensajeria');
    Route::get('/mensajeria/{conversation}/messages', [MensajeriaController::class, 'getMessages']);
    Route::post('/mensajeria/send', [MensajeriaController::class, 'sendMessage'])->name('mensajeria.send');
    Route::post('/mensajeria/create', [MensajeriaController::class, 'createConversation'])->name('mensajeria.create');
    Route::delete('/mensajeria/{conversation}', [MensajeriaController::class, 'deleteConversation']);
    
    // Document Management (Microservice)
    Route::get('/documentos', [DocumentosController::class, 'index'])->name('documentos');
    Route::post('/documentos', [DocumentosController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/{document}/download', [DocumentosController::class, 'download']);
    Route::delete('/documentos/{document}', [DocumentosController::class, 'destroy']);
    Route::post('/documentos/categories', [DocumentosController::class, 'createCategory'])->name('documentos.categories.store');
});
