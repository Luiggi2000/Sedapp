    <?php

    use App\Http\Controllers\Auth\AuthenticatedSessionTecController;
    use App\Http\Controllers\EvidenciaController;
    use App\Http\Controllers\HistorialController;
    use App\Http\Controllers\OrdenCorteController;
    use App\Http\Controllers\ProfileController;
    use App\Http\Controllers\RolController;
    use App\Http\Controllers\RoleController;
    use App\Http\Controllers\UserController;
    use App\Http\Controllers\ZonaController;
        use App\Http\Controllers\DashboardController;

    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Artisan;

    Route::get('/', function () {
        return view('auth/login');
    });

 Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

    // Login Técnico
    Route::get('/login-tecnico', [AuthenticatedSessionTecController::class, 'create'])->name('login.tecnico');
    Route::post('/login-tecnico', [AuthenticatedSessionTecController::class, 'store']);
    Route::post('/logout-tecnico', [AuthenticatedSessionTecController::class, 'destroy'])->name('logout.tecnico');

    // Login Supervisor
    Route::get('/login-supervisor', [AuthenticatedSessionTecController::class, 'create'])->name('login.supervisor');
    Route::post('/login-supervisor', [AuthenticatedSessionTecController::class, 'store']);
    Route::post('/logout-supervisor', [AuthenticatedSessionTecController::class, 'destroy'])->name('logout.supervisor');

    // Rutas generales para perfil
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // 🔐 RUTAS PARA ADMINISTRADOR Y SUPERVISOR (admin, Supervisor)
    Route::middleware(['auth', 'role:Administrador'])->group(function () {
        Route::resource('roles', RoleController::class);
        Route::resource('zonas', ZonaController::class);
        Route::resource('users', UserController::class);

    });

    // 🔐 RUTAS SOLO PARA SUPERVISOR Y TÉCNICO (Supervisor, tecnico)
    Route::middleware(['auth', 'role:Supervisor,Administrador'])->group(function () {
        Route::resource('orden-cortes', OrdenCorteController::class);
        Route::resource('evidencias', EvidenciaController::class);
    });

    // 🔐 RUTAS SOLO PARA TÉCNICO DE CAMPO (tecnico)
    Route::middleware(['auth', 'role:Tecnico'])->group(function () {
    // Mostrar la lista de órdenes para el técnico autenticado

Route::get('/mis-ordenes', [OrdenCorteController::class, 'misOrdenes'])->name('mis-ordenes.index');

Route::get('/mis-ordenes/{orden}', [OrdenCorteController::class, 'showMisOrden'])->name('mis-ordenes.show');
    // Acción para "Tomar Orden" (PATCH)
    Route::patch('/mis-ordenes/{orden}/tomar', [OrdenCorteController::class, 'tomarOrden'])->name('mis-ordenes.tomarOrden');

    // Actualizar estado de la orden (PATCH)
    Route::patch('/mis-ordenes/{id}/actualizar-estado', [OrdenCorteController::class, 'actualizarEstado'])->name('mis-ordenes.actualizar-estado');

    // Subir evidencia (POST)
    Route::post('/mis-ordenes/{id}/subir-evidencia', [OrdenCorteController::class, 'subirEvidencia'])->name('mis-ordenes.subirEvidencia');
});

Route::prefix('artisan')->group(function () {
    Route::get('/storage-link', function () {
        $exitCode = Artisan::call('storage:link');
        return 'storage:link';
    });

    Route::get('/optimize', function () {
        $exitCode = Artisan::call('optimize');
        return 'optimize';
    });

    Route::get('/config-cache', function () {
        $exitCode = Artisan::call('config:cache');
        return 'config:cache';
    });

    Route::get('/config-clear', function () {
        $exitCode = Artisan::call('config:clear');
        return 'config:clear';
    });

    Route::get('/cache-clear', function () {
        $exitCode = Artisan::call('cache:clear');
        return 'cache:clear';
    });

    Route::get('/route-cache', function () {
        $exitCode = Artisan::call('route:cache');
        return 'route:cache';
    });

    Route::get('/route-clear', function () {
        $exitCode = Artisan::call('route:clear');
        return 'route:clear';
    });

    Route::get('/view-clear', function () {
        $exitCode = Artisan::call('view:clear');
        return 'view:clear';
    });

    Route::get('/cache-stats', function () {
        $cacheDriver = config('cache.default');
        $cacheSize = 'N/A';

        if ($cacheDriver === 'file') {
            $cacheDir = storage_path('framework/cache/data');
            $size = 0;

            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($cacheDir)) as $file) {
                if ($file->isFile()) {
                    $size += $file->getSize();
                }
            }

            $cacheSize = round($size / 1024 / 1024, 2) . ' MB';
        }

        return response()->json([
            'driver' => $cacheDriver,
            'status' => 'active',
            'size' => $cacheSize,
            'prefix' => config('cache.prefix')
        ]);
    });

});

    // Dashboards personalizados
    Route::get('/dashboard-tecnico', function () {
        return view('dashboard-tecnico');
    })->middleware(['auth', 'role:Tecnico'])->name('dashboard.tecnico');

    Route::get('/dashboard-supervisor', function () {
        return view('dashboard-supervisor');
    })->middleware(['auth', 'role:Supervisor'])->name('dashboard.supervisor');

    // Autenticación general
    require __DIR__ . '/auth.php';
