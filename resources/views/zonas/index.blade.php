@extends('layouts.app')

@section('title', 'Zonas')

@section('content')
<div class="p-6" x-data="zonasData()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: #023A91">Gestión de Zonas</h1>
            <p class="text-gray-600">Administra las zonas del sistema</p>
        </div>
        <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Zona
        </button>
    </div>

    <!-- Search and Stats -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
        <!-- Search -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-lg shadow p-4">
                <input 
                    type="text" 
                    x-model="searchTerm" 
                    @input="filterZonas()"
                    placeholder="Buscar zonas por nombre o descripción..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
        </div>
        
        <!-- Stats -->
        <div class="bg-white rounded-lg shadow p-4">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600" x-text="zonas.length"></div>
                <div class="text-sm text-gray-500">Total Zonas</div>
            </div>
        </div>
    </div>

    <!-- Zonas Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <template x-for="zona in filteredZonas" :key="zona.id">
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-all duration-200 p-6 border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center flex-1">
                        <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-lg font-medium text-gray-900 truncate" x-text="zona.nombre"></h3>
                            <p class="text-sm text-gray-500" x-text="(zona.orden_cortes_count || 0) + ' órdenes'"></p>
                        </div>
                    </div>
                    <div class="flex space-x-2 ml-2">
                        <button @click="openEditModal(zona)" 
                                class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition-colors"
                                title="Editar zona">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </button>
                        <button @click="confirmDelete(zona)" 
                                class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors"
                                title="Eliminar zona">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-gray-600 text-sm line-clamp-2" x-text="zona.descripcion || 'Sin descripción'"></p>
                </div>
                
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span x-text="'Creada: ' + new Date(zona.created_at).toLocaleDateString('es-ES')"></span>
                    <span x-text="'ID: ' + zona.id"></span>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="filteredZonas.length === 0" class="text-center py-12" x-cloak>
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay zonas</h3>
        <p class="mt-1 text-sm text-gray-500">
            <span x-show="searchTerm">No se encontraron zonas que coincidan con tu búsqueda.</span>
            <span x-show="!searchTerm">Comienza creando una nueva zona.</span>
        </p>
        <div class="mt-6" x-show="!searchTerm">
            <button @click="openCreateModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Nueva Zona
            </button>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $zonas->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" x-text="editingZona ? 'Editar Zona' : 'Crear Nueva Zona'"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Form -->
                <form @submit.prevent="submitForm()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre de la Zona <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            x-model="form.nombre" 
                            required 
                            maxlength="255"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Ej: Zona Norte, Centro, etc."
                        >
                        <p class="mt-1 text-xs text-gray-500">Máximo 255 caracteres</p>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea 
                            x-model="form.descripcion" 
                            rows="4" 
                            maxlength="1000"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Descripción opcional de la zona..."
                        ></textarea>
                        <p class="mt-1 text-xs text-gray-500">Máximo 1000 caracteres</p>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex justify-end gap-3">
                        <button 
                            type="button" 
                            @click="closeModal()" 
                            class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2"
                            :disabled="!form.nombre.trim()"
                        >
                            <svg x-show="!editingZona" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <svg x-show="editingZona" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span x-text="editingZona ? 'Actualizar Zona' : 'Crear Zona'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-2">Confirmar Eliminación</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        ¿Estás seguro de que quieres eliminar la zona 
                        <span class="font-medium" x-text="zoneToDelete?.nombre"></span>?
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex justify-center gap-3 mt-4">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="deleteZone()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('zonasData', () => ({
        zonas: @json($zonas->items()),
        filteredZonas: [],
        searchTerm: '',
        showModal: false,
        showDeleteModal: false,
        editingZona: null,
        zoneToDelete: null,
        form: {
            nombre: '',
            descripcion: ''
        },

        init() {
            this.filterZonas();
        },

        filterZonas() {
            this.filteredZonas = this.zonas.filter(zona => {
                const matchesSearch = zona.nombre.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                    (zona.descripcion && zona.descripcion.toLowerCase().includes(this.searchTerm.toLowerCase()));
                return matchesSearch;
            });
        },

        openCreateModal() {
            this.form = { nombre: '', descripcion: '' };
            this.editingZona = null;
            this.showModal = true;
        },

        openEditModal(zona) {
            this.form = {
                nombre: zona.nombre,
                descripcion: zona.descripcion || ''
            };
            this.editingZona = zona;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.editingZona = null;
        },

        async submitForm() {
            const url = this.editingZona ? `{{ url('zonas') }}/${this.editingZona.id}` : '{{ route('zonas.store') }}';
            const method = this.editingZona ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (response.ok) {
                    if (this.editingZona) {
                        const index = this.zonas.findIndex(z => z.id === this.editingZona.id);
                        this.zonas[index] = data.zona;
                        window.addActivity(`actualizó zona ${data.zona.nombre}`);
                    } else {
                        this.zonas.push(data.zona);
                        window.addActivity(`creó zona ${data.zona.nombre}`);
                    }
                    this.filterZonas();
                    this.closeModal();
                    alert(data.message);
                } else {
                    alert('Error: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de red o servidor.');
            }
        },

        confirmDelete(zona) {
            this.zoneToDelete = zona;
            this.showDeleteModal = true;
        },

        async deleteZone() {
            try {
                const response = await fetch(`{{ url('zonas') }}/${this.zoneToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    this.zonas = this.zonas.filter(z => z.id !== this.zoneToDelete.id);
                    window.addActivity(`eliminó zona ${this.zoneToDelete.nombre}`);
                    this.filterZonas();
                    this.showDeleteModal = false;
                    alert(data.message);
                } else {
                    alert('Error: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de red o servidor.');
            }
        }
    }));
});
</script>
@endsection
