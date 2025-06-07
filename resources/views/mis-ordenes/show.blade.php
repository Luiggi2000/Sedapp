

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

                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-200 text-green-800 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 p-4 bg-red-200 text-red-800 rounded">
                        <ul class="list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($orden->estado === 'pendiente')
                    <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded">
                        Debes <strong>tomar la orden</strong> desde la lista de órdenes antes de poder completarla o subir evidencia.
                    </div>
                    <a href="{{ route('mis-ordenes.index') }}" class="inline-block px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Volver a Mis Órdenes</a>

                @elseif($orden->estado === 'en proceso')
                    <form action="{{ route('mis-ordenes.subirEvidencia', $orden->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="evidencia" class="block font-medium text-sm text-gray-700">Subir archivo (imagen/pdf):</label>
                            <input type="file" name="evidencia" id="evidencia" required accept="image/*,application/pdf" class="mt-1 block w-full" />
                        </div>
                        <div class="mt-4">
                            <label for="observaciones" class="block font-medium text-sm text-gray-700">Observaciones:</label>
                            <textarea name="observaciones" id="observaciones" rows="3" class="mt-1 block w-full rounded border-gray-300 shadow-sm"></textarea>
                        </div>
                        <div class="mt-4">
                            <label for="estado_final" class="block font-medium text-sm text-gray-700">Estado final (opcional):</label>
                            <select name="estado_final" id="estado_final" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                                <option value="">-- Seleccione --</option>
                                <option value="completado" {{ $estadoFinalSelected == 'completado' ? 'selected' : '' }}>Completado</option>
                                <option value="no realizado" {{ $estadoFinalSelected == 'no realizado' ? 'selected' : '' }}>No realizado</option>
                            </select>
                        </div>
                        <button type="submit" class="mt-6 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Subir evidencia</button>
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
