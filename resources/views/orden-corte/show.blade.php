<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalle de Orden de Corte') }}
            </h2>
            <a href="{{ route('orden-cortes.index') }}" class="btn btn-secondary">
                {{ __('Volver') }}
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-2xl p-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm text-gray-700">
                    <div>
                        <dt class="font-medium text-gray-600">Zona</dt>
                        <dd>{{ $ordenCorte->zona->nombre ?? 'N/A' }}</dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-600">Dirección</dt>
                        <dd>{{ $ordenCorte->direccion ?? 'N/A' }}</dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-600">Técnico Asignado</dt>
                        <dd>{{ $ordenCorte->user->name ?? 'Sin asignar' }}</dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-600">Fecha</dt>
                        <dd>{{ $ordenCorte->fecha }}</dd>
                    </div>

                    <div>
                        <dt class="font-medium text-gray-600">Estado</dt>
                        <dd><span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded">{{ $ordenCorte->estado }}</span></dd>
                    </div>

                    <div class="md:col-span-2">
                        <dt class="font-medium text-gray-600">Observaciones</dt>
                        <dd class="whitespace-pre-wrap">{{ $ordenCorte->observaciones ?? '-' }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</x-app-layout>
