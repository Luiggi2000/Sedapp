@extends('layouts.app')

@section('title', 'Orden #' . $orden->id)

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Orden #{{ str_pad($orden->id, 4, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-gray-600 mt-1">Creada el {{ $orden->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full status-{{ $orden->estado }}">
                    {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                </span>
                <a href="{{ route('mis-ordenes.index') }}" 
                   class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400 transition-colors">
                    Volver
                </a>
            </div>
        </div>
    </div>

    <!-- Información de la Orden -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Detalles principales -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalles de la Orden</h3>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Zona</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $orden->zona->nombre ?? 'Sin zona asignada' }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Dirección</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $orden->direccion }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Fecha Programada</label>
                    <p class="mt-1 text-sm text-gray-900">{{ \Carbon\Carbon::parse($orden->fecha)->format('d/m/Y') }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Estado</label>
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full status-{{ $orden->estado }} mt-1">
                        {{ ucfirst(str_replace('_', ' ', $orden->estado)) }}
                    </span>
                </div>

                @if(isset($orden->observaciones) && $orden->observaciones)
                <div>
                    <label class="block text-sm font-medium text-gray-700">Observaciones</label>
                    <p class="mt-1 text-sm text-gray-900">{{ $orden->observaciones }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Información de personas -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Personas Involucradas</h3>
            
            <div class="space-y-4">
                @if($userRole === 'cliente')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Técnico Asignado</label>
                    @if($orden->tecnico)
                        <div class="mt-2 flex items-center">
                            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-blue-600">
                                    {{ strtoupper(substr($orden->tecnico->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $orden->tecnico->name }}</p>
                                <p class="text-sm text-gray-500">{{ $orden->tecnico->email }}</p>
                                @if($orden->tecnico->telefono)
                                    <p class="text-sm text-gray-500">{{ $orden->tecnico->telefono }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="mt-1 text-sm text-gray-500">Sin técnico asignado</p>
                    @endif
                </div>
                @endif

                @if($userRole === 'Tecnico')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Cliente</label>
                    @if($orden->afectado)
                        <div class="mt-2 flex items-center">
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                <span class="text-sm font-medium text-purple-600">
                                    {{ strtoupper(substr($orden->afectado->name, 0, 2)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $orden->afectado->name }}</p>
                                <p class="text-sm text-gray-500">{{ $orden->afectado->email }}</p>
                                @if($orden->afectado->telefono)
                                    <p class="text-sm text-gray-500">{{ $orden->afectado->telefono }}</p>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="mt-1 text-sm text-gray-500">Sin cliente asignado</p>
                    @endif
                </div>
                @endif
            </div>

            @if($userRole === 'Tecnico')
            <div class="mt-6 pt-4 border-t border-gray-200 space-y-3">
                @if($orden->estado === 'pendiente' && !$orden->tecnico_id)
                    <button onclick="tomarOrden({{ $orden->id }})" 
                            class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                        Tomar Orden
                    </button>
                @elseif(in_array($orden->estado, ['pendiente', 'en_proceso']) && $orden->tecnico_id === auth()->id())
                    <button onclick="openStatusModal({{ $orden->id }}, '{{ $orden->estado }}')" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        Actualizar Estado
                    </button>
                    <button onclick="openCancelModal({{ $orden->id }})" 
                            class="w-full bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                        Cancelar Orden
                    </button>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Evidencias -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Evidencias</h3>
            @if($userRole === 'Tecnico' && $orden->tecnico_id === auth()->id() && in_array($orden->estado, ['pendiente', 'en_proceso']))
            <button onclick="openEvidenceModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                Subir Evidencia
            </button>
            @endif
        </div>

        @if($orden->evidencias->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($orden->evidencias as $evidencia)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="aspect-w-16 aspect-h-9 mb-3">
                        <img src="{{ asset('storage/' . $evidencia->imagen) }}" 
                             alt="Evidencia {{ $evidencia->tipo }}"
                             class="w-full h-48 object-cover rounded-lg">
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $evidencia->tipo === 'antes' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($evidencia->tipo === 'durante' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }}">
                                {{ ucfirst($evidencia->tipo) }}
                            </span>
                            <span class="text-xs text-gray-500">{{ $evidencia->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        @if($evidencia->observaciones)
                        <p class="text-sm text-gray-600">{{ $evidencia->observaciones }}</p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay evidencias</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if($userRole === 'Tecnico')
                        Sube evidencias del trabajo realizado.
                    @else
                        El técnico aún no ha subido evidencias.
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

@if($userRole === 'Tecnico')
<!-- Modal para tomar orden -->
<script>
function tomarOrden(ordenId) {
    if (confirm('¿Estás seguro de que quieres tomar esta orden? Se cambiará automáticamente a "En Proceso".')) {
        fetch(`/mis-ordenes/${ordenId}/tomar`, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
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
</script>

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

<!-- Modal para subir evidencia -->
<div id="evidence-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Subir Evidencia</h3>
            <form id="evidence-form" enctype="multipart/form-data">
                <input type="hidden" name="orden_corte_id" value="{{ $orden->id }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Evidencia</label>
                    <select name="tipo" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="">Seleccionar tipo</option>
                        <option value="antes">Antes del trabajo</option>
                        <option value="durante">Durante el trabajo</option>
                        <option value="despues">Después del trabajo</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Imagen</label>
                    <input type="file" name="imagen" accept="image/*" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                    <textarea name="observaciones" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              placeholder="Describe la evidencia..." required></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeEvidenceModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Subir Evidencia
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
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

function openEvidenceModal() {
    document.getElementById('evidence-modal').classList.remove('hidden');
}

function closeEvidenceModal() {
    document.getElementById('evidence-modal').classList.add('hidden');
    document.getElementById('evidence-form').reset();
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

document.getElementById('evidence-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    
    // Deshabilitar botón durante la subida
    submitButton.disabled = true;
    submitButton.textContent = 'Subiendo...';
    
    fetch('/evidencias', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Evidencia subida exitosamente');
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Error al subir la evidencia'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al subir la evidencia');
    })
    .finally(() => {
        // Rehabilitar botón
        submitButton.disabled = false;
        submitButton.textContent = 'Subir Evidencia';
    });
});
</script>
@endif
@endsection
