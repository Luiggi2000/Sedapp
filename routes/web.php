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
    use Illuminate\Support\Facades\Route;
    use Illuminate\Support\Facades\Auth;

    Route::get('/', function () {
        return view('auth/login');
    });

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');

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

    // Mostrar detalle de una orden específica
    Route::get('/mis-ordenes/{orden}', [OrdenCorteController::class, 'showMisOrden'])->name('mis-ordenes.showmis-ordenes');

    // Acción para "Tomar Orden" (PATCH)
    Route::patch('/mis-ordenes/{orden}/tomar', [OrdenCorteController::class, 'tomarOrden'])->name('mis-ordenes.tomarOrden');

    // Actualizar estado de la orden (PATCH)
    Route::patch('/mis-ordenes/{id}/actualizar-estado', [OrdenCorteController::class, 'actualizarEstado'])->name('mis-ordenes.actualizar-estado');

    // Subir evidencia (POST)
    Route::post('/mis-ordenes/{id}/subir-evidencia', [OrdenCorteController::class, 'subirEvidencia'])->name('mis-ordenes.subirEvidencia');
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
