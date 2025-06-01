<!-- resources/views/welcome.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SEDAPP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Simple animación para la gota */
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .drop-animation {
            animation: bounce 1.5s infinite;
        }
    </style>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen">
    <div class="bg-white shadow-xl rounded-2xl p-8 flex flex-col items-center space-y-6 w-full max-w-md text-center">
<img src="{{ asset('storage/seda.jpg') }}" alt="Gota animada" class="w-24 h-24 drop-animation">

        <h1 class="text-3xl font-bold text-blue-600">SEDAPP</h1>
        <p class="text-gray-600">Llevamos la vida a tu hogar</p>

        <a href="{{ route('login') }}" class="mt-6 bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition duration-300">
            Iniciar Sesión
        </a>
    </div>
</body>
</html>
