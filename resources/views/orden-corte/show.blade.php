<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Show Orden') }}
            </h2>
            <a href="{{ route('orden-cortes.index') }}" class="btn btn-primary">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                    <h3 class="text-lg font-semibold">{{ __('Detalles de la Orden de Corte') }}</h3>
                    <a href="{{ route('orden-cortes.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        {{ __('Volver') }}
                    </a>
                </div>

                <div class="p-6 text-gray-900 space-y-4">
                    <div>
                        <strong>Zona:</strong>
                        <div class="text-sm text-gray-700">{{ $ordenCorte->zona->nombre ?? 'N/A' }}</div>
                    </div>
                    <div>
    <strong>Dirección:</strong>
    <div class="text-sm text-gray-700">{{ $ordenCorte->direccion ?? 'N/A' }}</div>
</div>

                    <div>
                        <strong>Usuario:</strong>
                        <div class="text-sm text-gray-700">{{ $ordenCorte->user->name ?? 'N/A' }}</div>
                    </div>

                    <div>
                        <strong>Fecha:</strong>
                        <div class="text-sm text-gray-700">{{ $ordenCorte->fecha }}</div>
                    </div>
                    <div>
                        <strong>Estado:</strong>
                        <div class="text-sm text-gray-700">{{ $ordenCorte->estado }}</div>
                    </div>
                    <div>
                        <strong>Observaciones:</strong>
                        <div class="text-sm text-gray-700">{{ $ordenCorte->observaciones }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
