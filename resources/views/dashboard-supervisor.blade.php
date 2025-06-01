<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-indigo-600 text-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="text-lg font-semibold">Admin Panel</div>
            <div class="space-x-4">
                <a href="{{ route('dashboard') }}" class="hover:bg-indigo-700 px-3 py-2 rounded">Dashboard</a>
                <a href="{{ route('users.index') }}" class="hover:bg-indigo-700 px-3 py-2 rounded">Usuarios</a>
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   class="hover:bg-indigo-700 px-3 py-2 rounded">Cerrar sesión</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow max-w-7xl mx-auto p-6">
        <h1 class="text-4xl font-bold mb-6 text-gray-900">Panel de Administración</h1>

        <section class="bg-white rounded-lg shadow p-6">
            <h2 class="text-2xl font-semibold mb-4">Resumen</h2>
            <p class="text-gray-700">
                Aquí puedes gestionar usuarios, revisar reportes y administrar la plataforma.
            </p>
            <!-- Más contenido administrativo aquí -->
        </section>
    </main>

    <footer class="bg-indigo-600 text-white text-center py-4">
        &copy; {{ date('Y') }} Mi Aplicación - Todos los derechos reservados
    </footer>

</body>
</html>
