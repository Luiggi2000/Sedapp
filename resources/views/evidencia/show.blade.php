<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800 leading-tight">
            Detalle de Evidencia
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white border border-gray-200 shadow-sm rounded-lg p-6">
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Orden Corte</h3>
                        <p class="text-lg text-gray-900 font-semibold">{{ $evidencia->orden_corte_id }}</p>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Imagen</h3>
                        <img src="{{ asset('storage/app/private/' . $evidencia->imagen) }}"
                             alt="Imagen de Evidencia"
                             class="mt-2 rounded border max-w-full h-auto max-h-80 object-contain" />
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Observaciones</h3>
                        <p class="text-gray-800 text-base mt-1">{{ $evidencia->observaciones }}</p>
                    </div>
                </div>

                <div class="mt-8">
                    <a href="{{ route('evidencias.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-100 text-sm text-gray-800 font-medium rounded-md hover:bg-gray-200 transition">
                        ← Volver al listado
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
