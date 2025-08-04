@extends('layouts.app')

@section('title', 'Evidencias')

@section('content')
<div class="p-6" x-data="evidenciasData()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: #023A91">Gestión de Evidencias</h1>
            <p class="text-gray-600">Administra las evidencias del sistema</p>
        </div>
        @if(in_array(auth()->user()->role->name, ['Administrador', 'Supervisor', 'Tecnico']))
        <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Subir Evidencia
        </button>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Evidencias</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="evidencias.length"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Antes</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getEvidenciasByTipo('antes').length"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Durante</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getEvidenciasByTipo('durante').length"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Después</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getEvidenciasByTipo('despues').length"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <input 
                type="text" 
                x-model="searchTerm" 
                @input="filterEvidencias()"
                placeholder="Buscar evidencias..." 
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
            >
            <select x-model="selectedTipo" @change="filterEvidencias()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los tipos</option>
                <option value="antes">Antes</option>
                <option value="durante">Durante</option>
                <option value="despues">Después</option>
            </select>
            <select x-model="selectedOrden" @change="filterEvidencias()" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Todas las órdenes</option>
                <template x-for="orden in ordenes" :key="orden.id">
                    <option :value="orden.id" x-text="'#' + orden.id.toString().padStart(4, '0') + ' - ' + (orden.zona?.nombre || 'Sin zona')"></option>
                </template>
            </select>
        </div>
    </div>

    <!-- Evidencias Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="evidencia in filteredEvidencias" :key="evidencia.id">
            <div class="bg-white rounded-lg shadow overflow-hidden hover:shadow-lg transition-shadow">
                <div class="aspect-w-16 aspect-h-9">
                    <img :src="'/storage/' + evidencia.imagen" :alt="'Evidencia #' + evidencia.id" class="w-full h-48 object-cover cursor-pointer" @click="viewImage('/storage/' + evidencia.imagen)">
                </div>
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-900" x-text="'Orden #' + (evidencia.orden_corte_id || '').toString().padStart(4, '0')"></span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                              :class="{
                                  'bg-yellow-100 text-yellow-800': evidencia.tipo === 'antes',
                                  'bg-blue-100 text-blue-800': evidencia.tipo === 'durante',
                                  'bg-green-100 text-green-800': evidencia.tipo === 'despues'
                              }"
                              x-text="evidencia.tipo?.toUpperCase() || 'N/A'">
                        </span>
                    </div>
                    <p class="text-sm text-gray-600 mb-3" x-text="evidencia.observaciones || 'Sin observaciones'"></p>
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <span x-text="new Date(evidencia.created_at).toLocaleDateString()"></span>
                        @if(in_array(auth()->user()->role->name, ['Administrador', 'Supervisor', 'Tecnico']))
                        <button @click="confirmDelete(evidencia)" class="text-red-600 hover:text-red-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="filteredEvidencias.length === 0" class="text-center py-12" x-cloak>
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay evidencias</h3>
        <p class="mt-1 text-sm text-gray-500">Comienza subiendo una nueva evidencia.</p>
        @if(in_array(auth()->user()->role->name, ['Administrador', 'Supervisor', 'Tecnico']))
        <div class="mt-6">
            <button @click="openCreateModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Subir Evidencia
            </button>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $evidencias->links() }}
    </div>

    <!-- Upload Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Subir Evidencia</h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form @submit.prevent="submitForm()" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Orden de Corte</label>
                        <select x-model="form.orden_corte_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar orden</option>
                            <template x-for="orden in ordenes" :key="orden.id">
                                <option :value="orden.id" x-text="'#' + orden.id.toString().padStart(4, '0') + ' - ' + (orden.zona?.nombre || 'Sin zona')"></option>
                            </template>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Evidencia</label>
                        <select x-model="form.tipo" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar tipo</option>
                            <option value="antes">Antes</option>
                            <option value="durante">Durante</option>
                            <option value="despues">Después</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Imagen</label>
                        <input type="file" @change="handleFileChange" accept="image/*" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea x-model="form.observaciones" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Describe la evidencia..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Subir</button>
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
                        ¿Estás seguro de que quieres eliminar esta evidencia?
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex justify-center gap-3 mt-4">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="deleteEvidencia()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Image Viewer Modal -->
    <div x-show="showImageModal" class="fixed inset-0 bg-black bg-opacity-75 overflow-y-auto h-full w-full z-50" x-cloak @click="closeImageModal()">
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative max-w-4xl max-h-full">
                <button @click="closeImageModal()" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <img :src="selectedImage" class="max-w-full max-h-full object-contain" @click.stop>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize server data
window.evidenciasServerData = @json($evidencias->items());
window.ordenesServerData = @json($ordenes);

document.addEventListener('alpine:init', () => {
    Alpine.data('evidenciasData', () => ({
        evidencias: window.evidenciasServerData || [],
        ordenes: window.ordenesServerData || [],
        filteredEvidencias: [],
        searchTerm: '',
        selectedTipo: '',
        selectedOrden: '',
        showModal: false,
        showDeleteModal: false,
        showImageModal: false,
        evidenciaToDelete: null,
        selectedImage: '',
        form: {
            orden_corte_id: '',
            tipo: '',
            imagen: null,
            observaciones: ''
        },

        init() {
            this.filterEvidencias();
        },

        filterEvidencias() {
            this.filteredEvidencias = this.evidencias.filter(evidencia => {
                const matchesSearch = (evidencia.observaciones || '').toLowerCase().includes(this.searchTerm.toLowerCase());
                const matchesTipo = this.selectedTipo === '' || evidencia.tipo === this.selectedTipo;
                const matchesOrden = this.selectedOrden === '' || evidencia.orden_corte_id == this.selectedOrden;
                return matchesSearch && matchesTipo && matchesOrden;
            });
        },

        getEvidenciasByTipo(tipo) {
            return this.evidencias.filter(evidencia => evidencia.tipo === tipo);
        },

        openCreateModal() {
            this.form = { orden_corte_id: '', tipo: '', imagen: null, observaciones: '' };
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
        },

        viewImage(imageSrc) {
            this.selectedImage = imageSrc;
            this.showImageModal = true;
        },

        closeImageModal() {
            this.showImageModal = false;
            this.selectedImage = '';
        },

        handleFileChange(event) {
            this.form.imagen = event.target.files[0];
        },

        async submitForm() {
            if (!this.form.orden_corte_id || !this.form.tipo || !this.form.imagen) {
                alert('Por favor, complete todos los campos requeridos.');
                return;
            }

            const formData = new FormData();
            formData.append('orden_corte_id', this.form.orden_corte_id);
            formData.append('tipo', this.form.tipo);
            formData.append('imagen', this.form.imagen);
            formData.append('observaciones', this.form.observaciones || '');

            try {
                const response = await fetch('/evidencias', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    // Add new evidencia to the list
                    this.evidencias.unshift(data.evidencia);
                    this.filterEvidencias();
                    this.closeModal();
                    alert(data.message || 'Evidencia subida exitosamente');
                } else {
                    alert('Error al subir evidencia: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de red o servidor.');
            }
        },

        confirmDelete(evidencia) {
            this.evidenciaToDelete = evidencia;
            this.showDeleteModal = true;
        },

        async deleteEvidencia() {
            if (!this.evidenciaToDelete) return;

            try {
                const response = await fetch(`/evidencias/${this.evidenciaToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    this.evidencias = this.evidencias.filter(e => e.id !== this.evidenciaToDelete.id);
                    this.filterEvidencias();
                    this.showDeleteModal = false;
                    this.evidenciaToDelete = null;
                    alert(data.message || 'Evidencia eliminada exitosamente');
                } else {
                    alert('Error al eliminar evidencia: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de red o servidor.');
            }
        }
    }));
});
</script>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection
