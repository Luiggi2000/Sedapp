<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SEDAPP</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        .drop-animation {
            animation: bounce 1.5s infinite;
        }
    </style>
</head>
<body class="bg-blue-50 min-h-screen flex items-center justify-center py-8">
    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md text-center space-y-6">
        <!-- Imagen animada -->
        <img src="{{ asset('storage/seda.jpg') }}" alt="Gota animada" class="w-24 h-24 mx-auto drop-animation">
        
        <!-- Título y Eslogan -->
        <h1 class="text-3xl font-bold text-blue-600">SEDAPP</h1>
        <p class="text-gray-600">Llevamos la vida a tu hogar</p>
        
        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        
        <!-- Formulario de login -->
        <form method="POST" action="{{ route('login') }}" class="text-left space-y-4">
            @csrf
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                <input id="password" class="block mt-1 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" 
                       type="password" name="password" required autocomplete="current-password" />
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">Recordarme</span>
                </label>
            </div>
            
            <!-- Botón y enlace de olvido de contraseña -->
            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                        ¿Olvidaste tu contraseña?
                    </a>
                @endif
                
                <button type="submit" class="ml-3 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Iniciar Sesión
                </button>
            </div>
        </form>
    </div>
</body>
</html>
