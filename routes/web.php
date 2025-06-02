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
    Route::middleware(['auth', 'role:tecnico'])->group(function () {
        Route::get('/mis-ordenes', [OrdenCorteController::class, 'misOrdenes'])->name('ordenes.tecnico');
        Route::post('/ordenes/{id}/evidencia', [EvidenciaController::class, 'storeEvidenciaTecnico'])->name('evidencias.store.tecnico');
    });

    // Dashboards personalizados
    Route::get('/dashboard-tecnico', function () {
        return view('dashboard-tecnico');
    })->middleware(['auth', 'role:tecnico'])->name('dashboard.tecnico');

    Route::get('/dashboard-supervisor', function () {
        return view('dashboard-supervisor');
    })->middleware(['auth', 'role:Supervisor'])->name('dashboard.supervisor');

    // Autenticación general
    require __DIR__ . '/auth.php';
