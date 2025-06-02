<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detalle Orden de Corte') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-semibold mb-4">Información de la Orden</h3>
                <p><strong>Zona:</strong> {{ $orden->zona->nombre ?? 'N/A' }}</p>
                <p><strong>Fecha:</strong> {{ $orden->fecha }}</p>
                <p><strong>Dirección:</strong> {{ $orden->direccion }}</p>
                <p><strong>Estado:</strong> <span class="capitalize">{{ $orden->estado }}</span></p>

                <hr class="my-6" />

                @if($orden->estado === 'pendiente')
                    <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded">
                        Debes <strong>tomar la orden</strong> desde la lista de órdenes antes de poder completarla o subir evidencia.
                    </div>
                    <a href="{{ route('mis-ordenes.index') }}" class="inline-block px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Volver a Mis Órdenes</a>

                @elseif($orden->estado === 'en proceso')
                    <form action="{{ route('orden-cortes.actualizar-estado', $orden->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4">
                            <label for="estado" class="block font-medium text-gray-700 mb-1">Cambiar Estado de la Orden</label>
                            <select id="estado" name="estado" required class="border border-gray-300 rounded px-3 py-2 w-full">
                                <option value="">-- Selecciona Estado --</option>
                                <option value="completada" {{ $orden->estado == 'completada' ? 'selected' : '' }}>Completada</option>
                                <option value="no realizada" {{ $orden->estado == 'no realizada' ? 'selected' : '' }}>No Realizada</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="evidencia" class="block font-medium text-gray-700 mb-1">Subir Evidencia</label>
                            <input type="file" name="evidencia" id="evidencia" accept="image/*,application/pdf" required class="border border-gray-300 rounded px-3 py-2 w-full" />
                        </div>

                        <div class="flex items-center space-x-4">
                            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Guardar Cambios
                            </button>

                            <a href="{{ route('mis-ordenes.index') }}" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                                Cancelar
                            </a>
                        </div>
                    </form>
                @else
                    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                        Esta orden está <strong>{{ ucfirst($orden->estado) }}</strong>. No se pueden hacer más cambios.
                    </div>
                    <a href="{{ route('mis-ordenes.index') }}" class="inline-block px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Volver a Mis Órdenes</a>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
