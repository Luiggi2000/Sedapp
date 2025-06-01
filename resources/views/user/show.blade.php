<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white-800 leading-tight">
                {{ __('Mostrar Usuario') }}
            </h2>
            <a href="{{ route('users.index') }}" class="btn btn-primary">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden p-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                    <a href="{{ route('users.index') }}" class="text-sm bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md">
                        {{ __('Volver') }}
                    </a>
                </div>

                <div class="space-y-4 text-sm text-gray-700">
                    <div>
                        <span class="font-semibold">Nombre:</span>
                        {{ $user->name }}
                    </div>
                    <div>
                        <span class="font-semibold">Apellido:</span>
                        {{ $user->apellido }}
                    </div>
                    <div>
                        <span class="font-semibold">Teléfono:</span>
                        {{ $user->telefono }}
                    </div>
                    <div>
                        <span class="font-semibold">Rol:</span>
                        {{ $user->role->name }}
                    </div>
                    <div>
                        <span class="font-semibold">Email:</span>
                        {{ $user->email }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
