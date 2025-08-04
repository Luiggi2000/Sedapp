@extends('layouts.app')

@section('title', 'Mis Evidencias')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Mis Evidencias</h2>
                <p class="text-gray-600 mt-1">Consulta las evidencias fotográficas de tus órdenes de servicio</p>
            </div>
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-200 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-200 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600">Antes</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $stats['antes'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-200 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-600">Durante</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['durante'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-green-200 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-600">Después</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['despues'] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Buscar en observaciones..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tipo</label>
                <select name="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los tipos</option>
                    <option value="antes" {{ request('tipo') === 'antes' ? 'selected' : '' }}>Antes</option>
                    <option value="durante" {{ request('tipo') === 'durante' ? 'selected' : '' }}>Durante</option>
                    <option value="despues" {{ request('tipo') === 'despues' ? 'selected' : '' }}>Después</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Orden</label>
                <select name="orden_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas las órdenes</option>
                    @foreach($misOrdenes as $orden)
                        <option value="{{ $orden->id }}" {{ request('orden_id') == $orden->id ? 'selected' : '' }}>
                            #{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }} - {{ $orden->direccion }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Filtrar
                </button>
                <a href="{{ route('mis-evidencias.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Grid de Evidencias -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($evidencias->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 p-6">
                @foreach($evidencias as $evidencia)
                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                    <div class="aspect-w-16 aspect-h-12">
                        <img src="{{ Storage::url($evidencia->imagen) }}" 
                             alt="Evidencia {{ $evidencia->tipo }}"
                             class="w-full h-48 object-cover cursor-pointer"
                             onclick="viewImage('{{ Storage::url($evidencia->imagen) }}')">
                    </div>
                    <div class="p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $evidencia->tipo === 'antes' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($evidencia->tipo === 'durante' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($evidencia->tipo) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $evidencia->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        
                        <div class="mb-2">
                            <p class="text-sm font-medium text-gray-900">Orden #{{ str_pad($evidencia->orden_corte_id, 4, '0', STR_PAD_LEFT) }}</p>
                            <p class="text-xs text-gray-500">{{ $evidencia->ordenCorte->direccion }}</p>
                            @if($evidencia->ordenCorte->tecnico)
                                <p class="text-xs text-gray-500">Técnico: {{ $evidencia->ordenCorte->tecnico->name }}</p>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $evidencia->observaciones }}</p>
                        
                        <div class="flex justify-between items-center">
                            <button onclick="viewImage('{{ Storage::url($evidencia->imagen) }}')" 
                                    class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                Ver imagen
                            </button>
                            <a href="{{ route('mis-evidencias.show', $evidencia) }}" 
                               class="text-green-600 hover:text-green-800 text-sm font-medium">
                                Detalles
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Paginación -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $evidencias->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay evidencias</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron evidencias que coincidan con los filtros aplicados.</p>
            </div>
        @endif
    </div>
</div>

<!-- Modal para ver imagen -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50 hidden" onclick="closeImageModal()">
    <div class="max-w-4xl max-h-full p-4">
        <img id="modal-image" src="/placeholder.svg" class="max-w-full max-h-full object-contain" onclick="event.stopPropagation()">
    </div>
</div>

<script>
function viewImage(src) {
    document.getElementById('modal-image').src = src;
    document.getElementById('image-modal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
}

// Cerrar modal con tecla ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
@endsection
