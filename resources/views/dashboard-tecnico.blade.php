<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Panel de Técnico</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen">

    <header class="bg-gray-800 text-white p-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Técnico</h1>
            <nav>
                <a href="{{ route('dashboard.tecnico') }}" class="px-3 py-2 hover:bg-gray-700 rounded">Inicio</a>
                <a href="{{ route('users.index') }}" class="px-3 py-2 hover:bg-gray-700 rounded">Usuarios</a>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="px-3 py-2 hover:bg-gray-700 rounded">Cerrar sesión</a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </nav>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
        <h2 class="text-3xl font-semibold mb-6 text-gray-900">
            Bienvenido, {{ Auth::user()->name }}
        </h2>
        <div class="bg-white shadow-md rounded p-6">
            <!-- Aquí puedes poner contenido del panel -->
        </div>
    </main>

</body>

</html>
