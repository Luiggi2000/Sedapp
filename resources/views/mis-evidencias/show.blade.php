@extends('layouts.app')

@section('title', 'Evidencia #' . $evidencia->id)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Evidencia #{{ $evidencia->id }}</h2>
                <p class="text-gray-600 mt-1">Subida el {{ $evidencia->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                    {{ $evidencia->tipo === 'antes' ? 'bg-yellow-100 text-yellow-800' : 
                       ($evidencia->tipo === 'durante' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                    {{ ucfirst($evidencia->tipo) }}
                </span>
                <a href="{{ route('mis-evidencias.index') }}" 
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Contenido principal -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Imagen -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Imagen</h3>
            <div class="aspect-w-16 aspect-h-12">
                <img src="{{ Storage::url($evidencia->imagen) }}" 
                     alt="Evidencia {{ $evidencia->tipo }}"
                     class="w-full h-96 object-cover rounded-lg cursor-pointer"
                     onclick="viewFullImage('{{ Storage::url($evidencia->imagen) }}')">
            </div>
            <p class="text-sm text-gray-500 mt-2 text-center">Haz clic en la imagen para verla en tamaño completo</p>
        </div>

        <!-- Detalles -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalles de la Evidencia</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Orden de Trabajo</label>
                    <p class="mt-1 text-sm text-gray-900">
                        #{{ str_pad($evidencia->ordenCorte->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $evidencia->ordenCorte->direccion }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Zona</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $evidencia->ordenCorte->zona->nombre ?? 'Sin zona' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Técnico</label>
                    @if($evidencia->ordenCorte->tecnico)
                        <div class="mt-2 flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <span class="text-xs font-medium text-blue-600">
                                    {{ strtoupper(substr($evidencia->ordenCorte->tecnico->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $evidencia->ordenCorte->tecnico->name }}</p>
                                <p class="text-sm text-gray-500">{{ $evidencia->ordenCorte->tecnico->email }}</p>
                            </div>
                        </div>
                    @else
                        <p class="mt-1 text-sm text-gray-500">Sin técnico asignado</p>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Tipo de Evidencia</label>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1
                        {{ $evidencia->tipo === 'antes' ? 'bg-yellow-100 text-yellow-800' : 
                           ($evidencia->tipo === 'durante' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                        {{ ucfirst($evidencia->tipo) }}
                    </span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha de Subida</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $evidencia->created_at->format('d/m/Y H:i:s') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                    <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $evidencia->observaciones }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la orden -->
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Información de la Orden</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">Estado de la Orden</label>
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full mt-1 status-{{ $evidencia->ordenCorte->estado }}">
                    {{ ucfirst(str_replace('_', ' ', $evidencia->ordenCorte->estado)) }}
                </span>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha Programada</label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ \Carbon\Carbon::parse($evidencia->ordenCorte->fecha)->format('d/m/Y') }}
                </p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Fecha de Creación</label>
                <p class="mt-1 text-sm text-gray-900">
                    {{ $evidencia->ordenCorte->created_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>

        @if($evidencia->ordenCorte->observaciones)
        <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700">Observaciones de la Orden</label>
            <p class="mt-1 text-sm text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $evidencia->ordenCorte->observaciones }}</p>
        </div>
        @endif
    </div>
</div>

<!-- Modal para imagen completa -->
<div id="full-image-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden" onclick="closeFullImageModal()">
    <div class="max-w-full max-h-full p-4">
        <img id="full-modal-image" src="/placeholder.svg" class="max-w-full max-h-full object-contain" onclick="event.stopPropagation()">
    </div>
</div>

<script>
function viewFullImage(src) {
    document.getElementById('full-modal-image').src = src;
    document.getElementById('full-image-modal').classList.remove('hidden');
}

function closeFullImageModal() {
    document.getElementById('full-image-modal').classList.add('hidden');
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeFullImageModal();
    }
});
</script>
@endsection
