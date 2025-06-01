<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SEDAPP</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
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
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Formulario de login -->
        <form method="POST" action="{{ route('login.tecnico') }}">
            @csrf

            <!-- Email -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Remember Me -->
            <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

            <!-- Botón y enlace de olvido de contraseña -->
            <div class="flex items-center justify-between mt-4">
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900"
                        href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif

                <x-primary-button class="ml-3">
                    {{ __('Log in') }}
                </x-primary-button>
            </div>
        </form>
    </div>

</body>

</html>
