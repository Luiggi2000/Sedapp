@extends('layouts.app')

@section('title', 'Evidencias')

@section('content')
<div class="p-6" x-data="evidenciasData(@json($evidencias->items()), @json($ordenes))">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: #023A91">Gestión de Evidencias</h1>
            <p class="text-gray-600">Administra las evidencias del sistema</p>
        </div>
        <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Subir Evidencia
        </button>
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
                    <img :src="'/storage/' + evidencia.imagen" :alt="'Evidencia #' + evidencia.id" class="w-full h-48 object-cover">
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
                        <button @click="confirmDelete(evidencia)" class="text-red-600 hover:text-red-900">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $evidencias->links() }}
    </div>

    <!-- Upload Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Subir Evidencia</h3>
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
                        <textarea x-model="form.observaciones" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Subir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('evidenciasData', (evidencias, ordenes) => ({
            evidencias: evidencias,
            ordenes: ordenes,
            filteredEvidencias: [],
            searchTerm: '',
            selectedTipo: '',
            selectedOrden: '',
            showModal: false,
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
                    const matchesSearch = evidencia.observaciones.toLowerCase().includes(this.searchTerm.toLowerCase());
                    const matchesTipo = this.selectedTipo === '' || evidencia.tipo === this.selectedTipo;
                    const matchesOrden = this.selectedOrden === '' || evidencia.orden_corte_id === parseInt(this.selectedOrden);
                    return matchesSearch && matchesTipo && matchesOrden;
                });
            },

            openCreateModal() {
                this.form = { orden_corte_id: '', tipo: '', imagen: null, observaciones: '' };
                this.showModal = true;
            },

            handleFileChange(event) {
                this.form.imagen = event.target.files[0];
            },

            async submitForm() {
                if (!this.form.orden_corte_id || !this.form.tipo || !this.form.imagen || !this.form.observaciones) {
                    alert('Por favor, complete todos los campos.');
                    return;
                }

                const formData = new FormData();
                formData.append('orden_corte_id', this.form.orden_corte_id);
                formData.append('tipo', this.form.tipo);
                formData.append('imagen', this.form.imagen);
                formData.append('observaciones', this.form.observaciones);

                try {
                    const response = await fetch('{{ route('evidencias.store') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.evidencias.push(data.evidencia);
                        window.addActivity(`subió evidencia para orden ${this.form.orden_corte_id}`);
                        this.filterEvidencias();
                        this.showModal = false;
                        alert(data.message);
                    } else {
                        alert('Error al subir evidencia: ' + (data.message || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error de red o servidor.');
                }
            },

            closeModal() {
                this.showModal = false;
            },

            confirmDelete(evidencia) {
                if (confirm(`¿Está seguro de eliminar la evidencia de orden #${evidencia.orden_corte_id}?`)) {
                    this.deleteEvidencia(evidencia);
                }
            },

            async deleteEvidencia(evidencia) {
                try {
                    const response = await fetch(`{{ url('evidencias') }}/${evidencia.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.evidencias = this.evidencias.filter(e => e.id !== evidencia.id);
                        window.addActivity(`eliminó evidencia de orden ${evidencia.orden_corte_id}`);
                        this.filterEvidencias();
                        alert(data.message);
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
@endsection

