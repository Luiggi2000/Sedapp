@extends('layouts.app')

@section('title', 'Órdenes de Corte')

@section('content')
<div class="p-6" x-data="ordenesData()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color: #023A91">Órdenes de Corte</h1>
            <p class="text-gray-600">Gestiona las órdenes de corte del sistema</p>
        </div>
        <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nueva Orden
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Órdenes</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="ordenes.length"></p>
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
                    <p class="text-sm font-medium text-gray-600">Pendientes</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getOrdersByStatus('pendiente').length"></p>
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
                    <p class="text-sm font-medium text-gray-600">En Proceso</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getOrdersByStatus('en_proceso').length"></p>
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
                    <p class="text-sm font-medium text-gray-600">Completadas</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getOrdersByStatus('completada').length"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
            <div class="lg:col-span-2">
                <input 
                    type="text" 
                    x-model="searchTerm" 
                    @input="filterOrdenes()"
                    placeholder="Buscar por dirección, zona o técnico..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            <div>
                <select x-model="selectedEstado" @change="filterOrdenes()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los estados</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="en_proceso">En Proceso</option>
                    <option value="completada">Completada</option>
                    <option value="cancelada">Cancelada</option>
                </select>
            </div>
            <div>
                <select x-model="selectedZona" @change="filterOrdenes()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas las zonas</option>
                    <template x-for="zona in zonas" :key="zona.id">
                        <option :value="zona.id" x-text="zona.nombre"></option>
                    </template>
                </select>
            </div>
            <div>
                <select x-model="selectedTecnico" @change="filterOrdenes()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los técnicos</option>
                    <template x-for="tecnico in tecnicos" :key="tecnico.id">
                        <option :value="tecnico.id" x-text="tecnico.name"></option>
                    </template>
                </select>
            </div>
            <div>
                <button @click="clearFilters()" class="w-full px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zona</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Técnico</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Afectado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dirección</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="orden in filteredOrdenes" :key="orden.id">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <span x-text="'#' + String(orden.id).padStart(4, '0')"></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="orden.zona?.nombre || 'N/A'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="orden.tecnico?.name || 'N/A'"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="orden.afectado?.name || 'N/A'"></td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate" :title="orden.direccion" x-text="orden.direccion"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="new Date(orden.fecha).toLocaleDateString('es-ES')"></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                      :class="getEstadoClass(orden.estado)" 
                                      x-text="getEstadoText(orden.estado)">
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <button @click="openEditModal(orden)" 
                                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition-colors"
                                            title="Editar orden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <button @click="confirmDelete(orden)" 
                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors"
                                            title="Eliminar orden">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Empty State -->
        <div x-show="filteredOrdenes.length === 0" class="text-center py-12" x-cloak>
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay órdenes</h3>
            <p class="mt-1 text-sm text-gray-500">
                <span x-show="hasActiveFilters()">No se encontraron órdenes que coincidan con los filtros aplicados.</span>
                <span x-show="!hasActiveFilters()">Comienza creando una nueva orden de corte.</span>
            </p>
            <div class="mt-6" x-show="!hasActiveFilters()">
                <button @click="openCreateModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Nueva Orden
                </button>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $ordenes->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-10 mx-auto p-5 border w-full max-w-4xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900" x-text="editingOrden ? 'Editar Orden de Corte' : 'Crear Nueva Orden de Corte'"></h3>
                    <button @click="closeModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Form -->
                <form @submit.prevent="submitForm()">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Zona -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Zona <span class="text-red-500">*</span>
                            </label>
                            <select x-model="form.zona_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Seleccionar zona</option>
                                <template x-for="zona in zonas" :key="zona.id">
                                    <option :value="zona.id" x-text="zona.nombre"></option>
                                </template>
                            </select>
                        </div>
                        
                        <!-- Técnico -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Técnico Asignado <span class="text-red-500">*</span>
                            </label>
                            <select x-model="form.tecnico_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Seleccionar técnico</option>
                                <template x-for="tecnico in tecnicos" :key="tecnico.id">
                                    <option :value="tecnico.id" x-text="tecnico.name"></option>
                                </template>
                            </select>
                        </div>
                        
                        <!-- Afectado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Cliente Afectado <span class="text-red-500">*</span>
                            </label>
                            <select x-model="form.afectado_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Seleccionar cliente</option>
                                <template x-for="afectado in afectados" :key="afectado.id">
                                    <option :value="afectado.id" x-text="afectado.name"></option>
                                </template>
                            </select>
                        </div>
                        
                        <!-- Fecha -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Fecha Programada <span class="text-red-500">*</span>
                            </label>
                            <input type="date" x-model="form.fecha" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <!-- Dirección -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Dirección <span class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="form.direccion" required maxlength="500" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Dirección completa donde se realizará el corte">
                            <p class="mt-1 text-xs text-gray-500">Máximo 500 caracteres</p>
                        </div>
                        
                        <!-- Estado -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Estado <span class="text-red-500">*</span>
                            </label>
                            <select x-model="form.estado" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="pendiente">Pendiente</option>
                                <option value="en_proceso">En Proceso</option>
                                <option value="completada">Completada</option>
                                <option value="cancelada">Cancelada</option>
                            </select>
                        </div>
                        
                        <!-- Observaciones -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Observaciones</label>
                            <textarea x-model="form.observaciones" rows="4" maxlength="1000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Observaciones adicionales sobre la orden..."></textarea>
                            <p class="mt-1 text-xs text-gray-500">Máximo 1000 caracteres</p>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
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
                            :disabled="!isFormValid()"
                        >
                            <svg x-show="!editingOrden" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            <svg x-show="editingOrden" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span x-text="editingOrden ? 'Actualizar Orden' : 'Crear Orden'"></span>
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
                        ¿Estás seguro de que quieres eliminar la orden 
                        <span class="font-medium" x-text="orderToDelete ? '#' + String(orderToDelete.id).padStart(4, '0') : ''"></span>?
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex justify-center gap-3 mt-4">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="deleteOrder()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
window.ordenesServerData = @json($ordenes->items());
window.zonasServerData = @json($zonas);
window.tecnicosServerData = @json($tecnicos);
window.afectadosServerData = @json($afectados);

document.addEventListener("alpine:init", () => {
  Alpine.data("ordenesData", () => ({
    ordenes: window.ordenesServerData || [],
    zonas: window.zonasServerData || [],
    tecnicos: window.tecnicosServerData || [],
    afectados: window.afectadosServerData || [],
    filteredOrdenes: [],
    searchTerm: "",
    selectedEstado: "",
    selectedZona: "",
    selectedTecnico: "",
    showModal: false,
    showDeleteModal: false,
    editingOrden: false,
    orderToDelete: null,
    selectedOrden: null,
    form: {
      zona_id: "",
      tecnico_id: "",
      afectado_id: "",
      fecha: "",
      direccion: "",
      estado: "pendiente",
      observaciones: "",
    },

    init() {
      this.filterOrdenes();
    },

    filterOrdenes() {
      this.filteredOrdenes = this.ordenes.filter((orden) => {
        const matchesSearch =
          orden.direccion.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
          (orden.zona?.nombre || "").toLowerCase().includes(this.searchTerm.toLowerCase()) ||
          (orden.tecnico?.name || "").toLowerCase().includes(this.searchTerm.toLowerCase());
        const matchesEstado = this.selectedEstado === "" || orden.estado === this.selectedEstado;
        const matchesZona = this.selectedZona === "" || orden.zona_id == this.selectedZona;
        const matchesTecnico = this.selectedTecnico === "" || orden.tecnico_id == this.selectedTecnico;
        return matchesSearch && matchesEstado && matchesZona && matchesTecnico;
      });
    },

    getOrdersByStatus(status) {
      return this.ordenes.filter((orden) => orden.estado === status);
    },

    getEstadoClass(estado) {
      return {
        pendiente: "bg-yellow-100 text-yellow-800",
        en_proceso: "bg-blue-100 text-blue-800",
        completada: "bg-green-100 text-green-800",
        cancelada: "bg-red-100 text-red-800",
      }[estado] || "bg-gray-100 text-gray-800";
    },

    getEstadoText(estado) {
      return {
        pendiente: "Pendiente",
        en_proceso: "En Proceso",
        completada: "Completada",
        cancelada: "Cancelada",
      }[estado] || estado;
    },

    hasActiveFilters() {
      return this.searchTerm || this.selectedEstado || this.selectedZona || this.selectedTecnico;
    },

    clearFilters() {
      this.searchTerm = "";
      this.selectedEstado = "";
      this.selectedZona = "";
      this.selectedTecnico = "";
      this.filterOrdenes();
    },

    openCreateModal() {
      this.form = {
        zona_id: "",
        tecnico_id: "",
        afectado_id: "",
        fecha: new Date().toISOString().split("T")[0],
        direccion: "",
        estado: "pendiente",
        observaciones: "",
      };
      this.editingOrden = false;
      this.selectedOrden = null;
      this.showModal = true;
    },

    openEditModal(orden) {
      this.form = { ...orden };
      this.editingOrden = true;
      this.selectedOrden = orden;
      this.showModal = true;
    },

    closeModal() {
      this.showModal = false;
      this.editingOrden = false;
      this.selectedOrden = null;
    },

    isFormValid() {
      return (
        this.form.zona_id &&
        this.form.tecnico_id &&
        this.form.afectado_id &&
        this.form.fecha &&
        this.form.direccion.trim() &&
        this.form.estado
      );
    },

    async submitForm() {
      if (!this.isFormValid()) {
        alert("Por favor, complete todos los campos requeridos.");
        return;
      }

      try {
        const method = this.editingOrden ? "PATCH" : "POST";
        const url = this.editingOrden ? `/ordenes/${this.selectedOrden.id}` : "/ordenes";

        const response = await fetch(url, {
          method: method,
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
          body: JSON.stringify(this.form),
        });

        const data = await response.json();

        if (response.ok) {
          if (this.editingOrden) {
            const index = this.ordenes.findIndex((o) => o.id === this.selectedOrden.id);
            if (index !== -1) {
              this.ordenes[index] = {
                ...this.form,
                id: this.selectedOrden.id,
                zona: this.zonas.find((z) => z.id == this.form.zona_id),
                tecnico: this.tecnicos.find((t) => t.id == this.form.tecnico_id),
                afectado: this.afectados.find((a) => a.id == this.form.afectado_id),
              };
            }
          } else {
            const newId = Math.max(...this.ordenes.map((o) => o.id), 0) + 1;
            const newOrden = {
              ...this.form,
              id: newId,
              zona: this.zonas.find((z) => z.id == this.form.zona_id),
              tecnico: this.tecnicos.find((t) => t.id == this.form.tecnico_id),
              afectado: this.afectados.find((a) => a.id == this.form.afectado_id),
            };
            this.ordenes.push(newOrden);
          }
          this.filterOrdenes();
          this.closeModal();
          alert(data.message || "Orden guardada exitosamente");
        } else {
          alert("Error al guardar orden: " + (data.message || "Error desconocido"));
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Error de red o servidor.");
      }
    },

    confirmDelete(orden) {
      this.orderToDelete = orden;
      this.showDeleteModal = true;
    },

    async deleteOrder() {
      if (!this.orderToDelete) return;

      try {
        const response = await fetch(`/ordenes/${this.orderToDelete.id}`, {
          method: "DELETE",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
          },
        });

        const data = await response.json();

        if (response.ok) {
          this.ordenes = this.ordenes.filter((orden) => orden.id !== this.orderToDelete.id);
          this.filterOrdenes();
          this.showDeleteModal = false;
          this.orderToDelete = null;
          alert(data.message || "Orden eliminada exitosamente");
        } else {
          alert("Error al eliminar orden: " + (data.message || "Error desconocido"));
        }
      } catch (error) {
        console.error("Error:", error);
        alert("Error de red o servidor.");
      }
    },
  }));
});
</script>

@endsection
