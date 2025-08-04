@extends('layouts.app')

@section('title', 'Mis Órdenes')

@section('content')
<div class="space-y-6">
    <!-- Header con estadísticas -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">
                    @if($userRole === 'Tecnico')
                        Mis Órdenes Asignadas
                    @else
                        Mis Órdenes de Servicio
                    @endif
                </h2>
                <p class="text-gray-600 mt-1">
                    @if($userRole === 'Tecnico')
                        Gestiona y actualiza el estado de las órdenes que tienes asignadas
                    @else
                        Consulta el estado y progreso de tus órdenes de servicio
                    @endif
                </p>
            </div>
            
            @if($userRole === 'Tecnico')
            <div class="flex space-x-3">
                <button onclick="refreshOrders()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Actualizar
                </button>
            </div>
            @endif
        </div>

        <!-- Estadísticas -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-gray-200 rounded-lg">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                </div>
            </div>

            @if($userRole === 'Tecnico' && $stats['disponibles'] > 0)
            <div class="bg-purple-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-200 rounded-lg">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-600">Disponibles</p>
                        <p class="text-2xl font-bold text-purple-900">{{ $stats['disponibles'] }}</p>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-yellow-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-200 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-600">Pendientes</p>
                        <p class="text-2xl font-bold text-yellow-900">{{ $stats['pendientes'] }}</p>
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
                        <p class="text-sm font-medium text-blue-600">En Proceso</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['en_proceso'] }}</p>
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
                        <p class="text-sm font-medium text-green-600">Completadas</p>
                        <p class="text-2xl font-bold text-green-900">{{ $stats['completadas'] }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="p-2 bg-red-200 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-red-600">Canceladas</p>
                        <p class="text-2xl font-bold text-red-900">{{ $stats['canceladas'] }}</p>
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
                       placeholder="Buscar por dirección o zona..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="pendiente" {{ request('estado') === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="en_proceso" {{ request('estado') === 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                    <option value="completada" {{ request('estado') === 'completada' ? 'selected' : '' }}>Completada</option>
                    <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Zona</label>
                <select name="zona_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Todas las zonas</option>
                    @foreach($zonas as $zona)
                        <option value="{{ $zona->id }}" {{ request('zona_id') == $zona->id ? 'selected' : '' }}>
                            {{ $zona->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                    Filtrar
                </button>
                <a href="{{ route('mis-ordenes.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Limpiar
                </a>
            </div>
        </form>
    </div>

    <!-- Lista de Órdenes -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        @if($ordenes->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Orden
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Zona / Dirección
                            </th>
                            @if($userRole === 'cliente')
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Técnico
                            </th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($ordenes as $orden)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</div>
                                <div class="text-sm text-gray-500">{{ $orden->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $orden->zona->nombre ?? 'Sin zona' }}</div>
                                <div class="text-sm text-gray-500">{{ $orden->direccion }}</div>
                            </td>
                            @if($userRole === 'cliente')
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($orden->tecnico)
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                            <span class="text-xs font-medium text-blue-600">
                                                {{ strtoupper(substr($orden->tecnico->name, 0, 2)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $orden->tecnico->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $orden->tecnico->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-sm text-gray-500">Sin asignar</span>
                                @endif
                            </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full status-{{ $orden->estado }}">
                                    {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('mis-ordenes.show', $orden) }}" 
                                       class="text-blue-600 hover:text-blue-900">Ver</a>
                                    
                                    @if($userRole === 'Tecnico')
                                        @if($orden->estado === 'pendiente' && !$orden->tecnico_id)
                                            <button onclick="tomarOrden({{ $orden->id }})" 
                                                    class="text-purple-600 hover:text-purple-900">
                                                Tomar
                                            </button>
                                        @elseif(in_array($orden->estado, ['pendiente', 'en_proceso']) && $orden->tecnico_id === auth()->id())
                                            <button onclick="openStatusModal({{ $orden->id }}, '{{ $orden->estado }}')" 
                                                    class="text-green-600 hover:text-green-900">
                                                Actualizar
                                            </button>
                                            <button onclick="openCancelModal({{ $orden->id }})" 
                                                    class="text-red-600 hover:text-red-900">
                                                Cancelar
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $ordenes->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay órdenes</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($userRole === 'Tecnico')
                        No tienes órdenes asignadas en este momento.
                    @else
                        No tienes órdenes de servicio registradas.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

@if($userRole === 'Tecnico')
<!-- Modal para actualizar estado -->
<div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Actualizar Estado de Orden</h3>
            <form id="status-form">
                <input type="hidden" id="orden-id" name="orden_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select id="nuevo-estado" name="estado" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="pendiente">Pendiente</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="completada">Completada</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                    <textarea id="observaciones" name="observaciones" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Agregar observaciones sobre el cambio de estado..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para cancelar orden -->
<div id="cancel-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Cancelar Orden</h3>
            <form id="cancel-form">
                <input type="hidden" id="cancel-orden-id" name="orden_id">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Motivo de Cancelación</label>
                    <textarea id="motivo-cancelacion" name="motivo_cancelacion" rows="4" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Explica el motivo por el cual cancelas esta orden..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeCancelModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Confirmar Cancelación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function tomarOrden(ordenId) {
    if (confirm('¿Estás seguro de que quieres tomar esta orden? Se cambiará automáticamente a "En Proceso".')) {
        fetch(`/mis-ordenes/${ordenId}/tomar`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al tomar la orden');
        });
    }
}

function openStatusModal(ordenId, estadoActual) {
    document.getElementById('orden-id').value = ordenId;
    document.getElementById('nuevo-estado').value = estadoActual;
    document.getElementById('status-modal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('status-modal').classList.add('hidden');
    document.getElementById('status-form').reset();
}

function openCancelModal(ordenId) {
    document.getElementById('cancel-orden-id').value = ordenId;
    document.getElementById('cancel-modal').classList.remove('hidden');
}

function closeCancelModal() {
    document.getElementById('cancel-modal').classList.add('hidden');
    document.getElementById('cancel-form').reset();
}

document.getElementById('status-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const ordenId = document.getElementById('orden-id').value;
    const formData = new FormData(this);
    
    fetch(`/mis-ordenes/${ordenId}/change-status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el estado');
    });
});

document.getElementById('cancel-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const ordenId = document.getElementById('cancel-orden-id').value;
    const formData = new FormData(this);
    
    fetch(`/mis-ordenes/${ordenId}/cancelar`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cancelar la orden');
    });
});

function refreshOrders() {
    location.reload();
}
</script>
@endif
@endsection
