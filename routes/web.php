<?php

use App\Http\Controllers\EvidenciaController;
use App\Http\Controllers\Auth\AuthenticatedSessionTecController;
use App\Http\Controllers\EvidenciaTecController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\OrdenCorteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZonaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Login técnico (GET)
Route::get('/login-tecnico', [AuthenticatedSessionTecController::class, 'create'])
    ->name('login.tecnico');

// Autenticación técnica (POST)
Route::post('/login-tecnico', [AuthenticatedSessionTecController::class, 'store']);

// Logout técnico
Route::post('/logout-tecnico', [AuthenticatedSessionTecController::class, 'destroy'])
    ->name('logout.tecnico');

    // Login Supervisor (GET)
Route::get('/login-supervisor', [AuthenticatedSessionTecController::class, 'create'])
    ->name('login.supervisor');

// Autenticación Supervisor (POST)
Route::post('/login-supervisor', [AuthenticatedSessionTecController::class, 'store']);

// Logout Supervisor
Route::post('/logout-tecnico', [AuthenticatedSessionTecController::class, 'destroy'])
    ->name('logout.supervisor');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');

    Route::get('rolesypermisos/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('rolesypermisos/roles', [RoleController::class, 'store'])->name('roles.store');

    Route::get('rolesypermisos/roles/{role}/edit', [RoleController::class, 'edit'])->name('role.edit');
    Route::put('rolesypermisos/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::resource('users', UserController::class);
});
Route::resource('roles', RoleController::class);
Route::resource('zonas', ZonaController::class);
Route::resource('orden-cortes', OrdenCorteController::class);
Route::resource('evidencias', EvidenciaController::class);

Route::get('/dashboard-tecnico', function () {
    return view('dashboard-tecnico'); // crea resources/views/dashboard-tecnico.blade.php
})->middleware('auth')->name('dashboard.tecnico');

Route::get('/dashboard-supervisor', function () {
    return view('dashboard-supervisor'); // crea resources/views/dashboard-tecnico.blade.php
})->middleware('auth')->name('dashboard.supervisor');


require __DIR__ . '/auth.php';
