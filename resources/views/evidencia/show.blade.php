<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show') }} {{ __('Evidencia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <div class="mb-4">
                    <strong class="block text-gray-700 font-semibold mb-1">Orden Corte Id:</strong>
                    <span class="text-gray-900">{{ $evidencia->orden_corte_id }}</span>
                </div>
                <div class="mb-4">
                    <strong class="block text-gray-700 font-semibold mb-1">Imagen:</strong>
                    <img src="{{ asset('storage/' . $evidencia->imagen) }}" alt="Imagen Evidencia"
                        style="max-height: 100px;">
                </div>
                <div>
                    <strong>Observaciones:</strong>
                    <div class="text-sm text-gray-700">{{ $evidencia->observaciones }}</div>
                </div>
                <div class="mt-6">
                    <a href="{{ route('evidencias.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        {{ __('Back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
