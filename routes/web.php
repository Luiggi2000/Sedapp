<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\OrdenesController;
use App\Http\Controllers\EvidenciasController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\MensajeriaController;
use App\Http\Controllers\DocumentosController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Ruta raíz redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// Rutas de autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
});

// Dashboard - requiere autenticación y verificación
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas generales para perfil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de gestión de usuarios
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/usuarios', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/create', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/usuarios', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/usuarios/{user}', [UserController::class, 'show'])->name('usuarios.show');
    Route::get('/usuarios/{user}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::patch('/usuarios/{user}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    Route::patch('/usuarios/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('usuarios.toggle-status');
});

// Rutas de gestión de roles
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
    Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
    Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->name('roles.show');
    Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
    Route::patch('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
});

// Rutas de gestión de órdenes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/ordenes', [OrdenesController::class, 'index'])->name('ordenes.index');
    Route::get('/ordenes/create', [OrdenesController::class, 'create'])->name('ordenes.create');
    Route::post('/ordenes', [OrdenesController::class, 'store'])->name('ordenes.store');
    Route::get('/ordenes/{orden}', [OrdenesController::class, 'show'])->name('ordenes.show');
    Route::get('/ordenes/{orden}/edit', [OrdenesController::class, 'edit'])->name('ordenes.edit');
    Route::patch('/ordenes/{orden}', [OrdenesController::class, 'update'])->name('ordenes.update');
    Route::delete('/ordenes/{orden}', [OrdenesController::class, 'destroy'])->name('ordenes.destroy');
    Route::patch('/ordenes/{orden}/change-status', [OrdenesController::class, 'changeStatus'])->name('ordenes.change-status');
    Route::get('/ordenes/export/csv', [OrdenesController::class, 'exportCsv'])->name('ordenes.export.csv');
});

// Rutas de gestión de zonas
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/zonas', [ZoneController::class, 'index'])->name('zonas.index');
    Route::get('/zonas/create', [ZoneController::class, 'create'])->name('zonas.create');
    Route::post('/zonas', [ZoneController::class, 'store'])->name('zonas.store');
    Route::get('/zonas/{zona}', [ZoneController::class, 'show'])->name('zonas.show');
    Route::get('/zonas/{zona}/edit', [ZoneController::class, 'edit'])->name('zonas.edit');
    Route::patch('/zonas/{zona}', [ZoneController::class, 'update'])->name('zonas.update');
    Route::delete('/zonas/{zona}', [ZoneController::class, 'destroy'])->name('zonas.destroy');
    Route::get('/zonas/export/csv', [ZoneController::class, 'exportCsv'])->name('zonas.export.csv');
});

// Rutas de gestión de evidencias
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/evidencias', [EvidenciasController::class, 'index'])->name('evidencias.index');
    Route::get('/evidencias/create', [EvidenciasController::class, 'create'])->name('evidencias.create');
    Route::post('/evidencias', [EvidenciasController::class, 'store'])->name('evidencias.store');
    Route::get('/evidencias/{evidencia}', [EvidenciasController::class, 'show'])->name('evidencias.show');
    Route::get('/evidencias/{evidencia}/edit', [EvidenciasController::class, 'edit'])->name('evidencias.edit');
    Route::patch('/evidencias/{evidencia}', [EvidenciasController::class, 'update'])->name('evidencias.update');
    Route::delete('/evidencias/{evidencia}', [EvidenciasController::class, 'destroy'])->name('evidencias.destroy');
    Route::get('/evidencias/{evidencia}/download', [EvidenciasController::class, 'download'])->name('evidencias.download');
    Route::get('/evidencias/export/csv', [EvidenciasController::class, 'exportCsv'])->name('evidencias.export.csv');
});

// Rutas de mensajería
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/mensajeria', [MensajeriaController::class, 'index'])->name('mensajeria.index');
    Route::get('/mensajeria/{conversation}/messages', [MensajeriaController::class, 'getMessages'])->name('mensajeria.messages');
    Route::post('/mensajeria/send', [MensajeriaController::class, 'sendMessage'])->name('mensajeria.send');
    Route::post('/mensajeria/conversations', [MensajeriaController::class, 'createConversation'])->name('mensajeria.conversations.store');
    Route::patch('/mensajeria/{conversation}/mark-read', [MensajeriaController::class, 'markAsRead'])->name('mensajeria.mark-read');
    Route::delete('/mensajeria/conversations/{conversation}', [MensajeriaController::class, 'deleteConversation'])->name('mensajeria.conversations.destroy');
    Route::post('/mensajeria/upload', [MensajeriaController::class, 'uploadFile'])->name('mensajeria.upload');
});

// Rutas de documentos
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/documentos', [DocumentosController::class, 'index'])->name('documentos.index');
    Route::post('/documentos', [DocumentosController::class, 'store'])->name('documentos.store');
    Route::get('/documentos/{document}/download', [DocumentosController::class, 'download'])->name('documentos.download');
    Route::get('/documentos/{document}/preview', [DocumentosController::class, 'preview'])->name('documentos.preview');
    Route::delete('/documentos/{document}', [DocumentosController::class, 'destroy'])->name('documentos.destroy');
    Route::post('/documentos/categories', [DocumentosController::class, 'createCategory'])->name('documentos.categories.store');
    Route::post('/documentos/folders', [DocumentosController::class, 'createFolder'])->name('documentos.folders.store');
    Route::patch('/documentos/{document}/permissions', [DocumentosController::class, 'updatePermissions'])->name('documentos.permissions.update');
});

// Rutas API para AJAX
Route::middleware(['auth', 'verified'])->prefix('api')->group(function () {
    // API para usuarios
    Route::get('/usuarios/search', [UserController::class, 'search'])->name('api.usuarios.search');
    Route::get('/usuarios/stats', [UserController::class, 'stats'])->name('api.usuarios.stats');
    
    // API para órdenes
    Route::get('/ordenes/search', [OrdenesController::class, 'search'])->name('api.ordenes.search');
    Route::get('/ordenes/stats', [OrdenesController::class, 'stats'])->name('api.ordenes.stats');
    Route::get('/ordenes/{orden}/timeline', [OrdenesController::class, 'timeline'])->name('api.ordenes.timeline');
    
    // API para zonas
    Route::get('/zonas/search', [ZoneController::class, 'search'])->name('api.zonas.search');
    Route::get('/zonas/stats', [ZoneController::class, 'stats'])->name('api.zonas.stats');
    
    // API para evidencias
    Route::get('/evidencias/search', [EvidenciasController::class, 'search'])->name('api.evidencias.search');
    Route::get('/evidencias/stats', [EvidenciasController::class, 'stats'])->name('api.evidencias.stats');
    
    // API para dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('api.dashboard.stats');
    Route::get('/dashboard/recent-activity', [DashboardController::class, 'getRecentActivity'])->name('api.dashboard.recent-activity');
});

// Rutas de recuperación de contraseña (si se implementa)
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
    
    Route::post('/forgot-password', function () {
        // Implementar lógica de recuperación
        return back()->with('status', 'Enlace de recuperación enviado!');
    })->name('password.email');
});
