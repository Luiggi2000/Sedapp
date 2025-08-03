@extends('layouts.app')

@section('title', 'Gestión de Documentos')

@section('content')
<div class="p-6" x-data="documentosData()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: #023A91">Gestión de Documentos</h1>
            <p class="text-gray-600">Administra los documentos del sistema</p>
        </div>
        <div class="flex gap-2">
            <button @click="openCategoryModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                Nueva Categoría
            </button>
            <button @click="openUploadModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Subir Documento
            </button>
        </div>
    </div>

    @if(isset($error))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        <div class="flex">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            <span>{{ $error }}</span>
        </div>
    </div>
    @endif

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
                    <p class="text-sm font-medium text-gray-600">Total Documentos</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="documentos.length"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Categorías</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="categorias.length"></p>
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
                    <p class="text-sm font-medium text-gray-600">Recientes</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getRecentDocuments().length"></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-4">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Descargas</p>
                    <p class="text-2xl font-semibold text-gray-900" x-text="getTotalDownloads()"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="lg:col-span-2">
                <input 
                    type="text" 
                    x-model="searchTerm" 
                    @input="filterDocuments()"
                    placeholder="Buscar documentos por título o descripción..." 
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
            </div>
            <div>
                <select x-model="selectedCategory" @change="filterDocuments()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todas las categorías</option>
                    <template x-for="categoria in categorias" :key="categoria.id">
                        <option :value="categoria.id" x-text="categoria.name"></option>
                    </template>
                </select>
            </div>
            <div>
                <select x-model="selectedFileType" @change="filterDocuments()" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los tipos</option>
                    <option value="pdf">PDF</option>
                    <option value="doc">Word</option>
                    <option value="xls">Excel</option>
                    <option value="ppt">PowerPoint</option>
                    <option value="img">Imágenes</option>
                    <option value="txt">Texto</option>
                </select>
            </div>
            <div>
                <button @click="clearFilters()" class="w-full px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Limpiar Filtros
                </button>
            </div>
        </div>
    </div>

    <!-- Documents Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        <template x-for="documento in filteredDocuments" :key="documento.id">
            <div class="bg-white rounded-lg shadow hover:shadow-lg transition-all duration-200 p-6 border border-gray-200">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center flex-1">
                        <div class="flex-shrink-0 h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg x-show="getFileIcon(documento.file_type) === 'pdf'" class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="getFileIcon(documento.file_type) === 'doc'" class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="getFileIcon(documento.file_type) === 'xls'" class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="getFileIcon(documento.file_type) === 'img'" class="w-6 h-6 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="!['pdf', 'doc', 'xls', 'img'].includes(getFileIcon(documento.file_type))" class="w-6 h-6 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-4 flex-1">
                            <h3 class="text-sm font-medium text-gray-900 truncate" x-text="documento.title"></h3>
                            <p class="text-xs text-gray-500" x-text="documento.category?.name || 'Sin categoría'"></p>
                        </div>
                    </div>
                    <div class="flex space-x-1 ml-2">
                        <button @click="downloadDocument(documento)" 
                                class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition-colors"
                                title="Descargar documento">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                        </button>
                        <button @click="confirmDelete(documento)" 
                                class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition-colors"
                                title="Eliminar documento">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <div class="mb-4">
                    <p class="text-gray-600 text-xs line-clamp-2" x-text="documento.description || 'Sin descripción'"></p>
                </div>
                
                <div class="flex items-center justify-between text-xs text-gray-500">
                    <span x-text="formatFileSize(documento.file_size)"></span>
                    <span x-text="formatDate(documento.created_at)"></span>
                </div>
                
                <div class="mt-2 flex items-center justify-between text-xs text-gray-400">
                    <span x-text="'Por: ' + (documento.uploaded_by_name || 'Desconocido')"></span>
                    <span x-text="(documento.download_count || 0) + ' descargas'"></span>
                </div>
            </div>
        </template>
    </div>

    <!-- Empty State -->
    <div x-show="filteredDocuments.length === 0" class="text-center py-12" x-cloak>
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No hay documentos</h3>
        <p class="mt-1 text-sm text-gray-500">
            <span x-show="hasActiveFilters()">No se encontraron documentos que coincidan con los filtros aplicados.</span>
            <span x-show="!hasActiveFilters()">Comienza subiendo un nuevo documento.</span>
        </p>
        <div class="mt-6" x-show="!hasActiveFilters()">
            <button @click="openUploadModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Subir Documento
            </button>
        </div>
    </div>

    <!-- Upload Modal -->
    <div x-show="showUploadModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Subir Nuevo Documento</h3>
                    <button @click="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form @submit.prevent="uploadDocument()" enctype="multipart/form-data">
                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Título <span class="text-red-500">*</span>
                            </label>
                            <input type="text" x-model="uploadForm.title" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Categoría <span class="text-red-500">*</span>
                            </label>
                            <select x-model="uploadForm.category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">Seleccionar categoría</option>
                                <template x-for="categoria in categorias" :key="categoria.id">
                                    <option :value="categoria.id" x-text="categoria.name"></option>
                                </template>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Archivo <span class="text-red-500">*</span>
                            </label>
                            <input type="file" @change="handleFileChange" required accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <p class="mt-1 text-xs text-gray-500">Máximo 10MB. Formatos: PDF, Word, Excel, PowerPoint, Texto, Imágenes</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                            <textarea x-model="uploadForm.description" rows="4" maxlength="1000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Descripción opcional del documento..."></textarea>
                        </div>
                    </div>
                    
                    <div class="flex justify-end gap-3 mt-6 pt-4 border-t border-gray-200">
                        <button type="button" @click="closeUploadModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            Subir Documento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Category Modal -->
    <div x-show="showCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Nueva Categoría</h3>
                    <button @click="closeCategoryModal()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form @submit.prevent="createCategory()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" x-model="categoryForm.name" required maxlength="255" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Descripción</label>
                        <textarea x-model="categoryForm.description" rows="3" maxlength="500" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closeCategoryModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                            Crear Categoría
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
                        ¿Estás seguro de que quieres eliminar el documento 
                        <span class="font-medium" x-text="documentToDelete?.title"></span>?
                        Esta acción no se puede deshacer.
                    </p>
                </div>
                <div class="flex justify-center gap-3 mt-4">
                    <button @click="showDeleteModal = false" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </button>
                    <button @click="deleteDocument()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('documentosData', () => ({
        documentos: @json($documentos ?? []),
        categorias: @json($categorias ?? []),
        filteredDocuments: [],
        searchTerm: '',
        selectedCategory: '',
        selectedFileType: '',
        showUploadModal: false,
        showCategoryModal: false,
        showDeleteModal: false,
        documentToDelete: null,
        uploadForm: {
            title: '',
            description: '',
            category_id: '',
            file: null
        },
        categoryForm: {
            name: '',
            description: ''
        },

        init() {
            this.filterDocuments();
        },

        filterDocuments() {
            this.filteredDocuments = this.documentos.filter(doc => {
                const matchesSearch = doc.title.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                    (doc.description && doc.description.toLowerCase().includes(this.searchTerm.toLowerCase()));
                const matchesCategory = this.selectedCategory === '' || doc.category_id === parseInt(this.selectedCategory);
                const matchesFileType = this.selectedFileType === '' || this.getFileTypeCategory(doc.file_type) === this.selectedFileType;
                return matchesSearch && matchesCategory && matchesFileType;
            });
        },

        getRecentDocuments() {
            const oneWeekAgo = new Date();
            oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);
            return this.documentos.filter(doc => new Date(doc.created_at) > oneWeekAgo);
        },

        getTotalDownloads() {
            return this.documentos.reduce((total, doc) => total + (doc.download_count || 0), 0);
        },

        hasActiveFilters() {
            return this.searchTerm !== '' || this.selectedCategory !== '' || this.selectedFileType !== '';
        },

        clearFilters() {
            this.searchTerm = '';
            this.selectedCategory = '';
            this.selectedFileType = '';
            this.filterDocuments();
        },

        getFileIcon(fileType) {
            if (!fileType) return 'file';
            const type = fileType.toLowerCase();
            if (type.includes('pdf')) return 'pdf';
            if (type.includes('doc') || type.includes('word')) return 'doc';
            if (type.includes('xls') || type.includes('excel') || type.includes('sheet')) return 'xls';
            if (type.includes('ppt') || type.includes('powerpoint')) return 'ppt';
            if (type.includes('image') || type.includes('jpg') || type.includes('jpeg') || type.includes('png')) return 'img';
            return 'file';
        },

        getFileTypeCategory(fileType) {
            if (!fileType) return 'other';
            const type = fileType.toLowerCase();
            if (type.includes('pdf')) return 'pdf';
            if (type.includes('doc') || type.includes('word')) return 'doc';
            if (type.includes('xls') || type.includes('excel')) return 'xls';
            if (type.includes('ppt') || type.includes('powerpoint')) return 'ppt';
            if (type.includes('image') || type.includes('jpg') || type.includes('jpeg') || type.includes('png')) return 'img';
            if (type.includes('text') || type.includes('txt')) return 'txt';
            return 'other';
        },

        formatFileSize(bytes) {
            if (!bytes) return '0 B';
            const k = 1024;
            const sizes = ['B', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        formatDate(dateString) {
            if (!dateString) return '';
            const date = new Date(dateString);
            return date.toLocaleDateString('es-ES', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
            });
        },

        openUploadModal() {
            this.uploadForm = { title: '', description: '', category_id: '', file: null };
            this.showUploadModal = true;
        },

        closeUploadModal() {
            this.showUploadModal = false;
        },

        openCategoryModal() {
            this.categoryForm = { name: '', description: '' };
            this.showCategoryModal = true;
        },

        closeCategoryModal() {
            this.showCategoryModal = false;
        },

        handleFileChange(event) {
            this.uploadForm.file = event.target.files[0];
        },

        async uploadDocument() {
            if (!this.uploadForm.title.trim() || !this.uploadForm.category_id || !this.uploadForm.file) {
                alert('Por favor, complete todos los campos requeridos.');
                return;
            }

            const formData = new FormData();
            formData.append('title', this.uploadForm.title);
            formData.append('description', this.uploadForm.description);
            formData.append('category_id', this.uploadForm.category_id);
            formData.append('file', this.uploadForm.file);

            try {
                const response = await fetch('{{ route('documentos.store') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (response.ok) {
                    this.documentos.push(data.document);
                    this.filterDocuments();
                    this.closeUploadModal();
                    window.addActivity(`subió documento ${data.document.title}`);
                    alert('Documento subido exitosamente');
                } else {
                    alert('Error: ' + (data.error || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        },

        async createCategory() {
            if (!this.categoryForm.name.trim()) {
                alert('Por favor, ingrese el nombre de la categoría.');
                return;
            }

            try {
                const response = await fetch('{{ route('documentos.categories.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.categoryForm)
                });

                const data = await response.json();

                if (response.ok) {
                    this.categorias.push(data.category);
                    this.closeCategoryModal();
                    window.addActivity(`creó categoría ${data.category.name}`);
                    alert('Categoría creada exitosamente');
                } else {
                    alert('Error: ' + (data.error || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        },

        async downloadDocument(documento) {
            try {
                const response = await fetch(`{{ url('documentos') }}/${documento.id}/download`);
                
                if (response.ok) {
                    const blob = await response.blob();
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.style.display = 'none';
                    a.href = url;
                    a.download = documento.filename || documento.title;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                    
                    // Incrementar contador de descargas
                    documento.download_count = (documento.download_count || 0) + 1;
                    window.addActivity(`descargó documento ${documento.title}`);
                } else {
                    alert('Error al descargar el documento');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        },

        confirmDelete(documento) {
            this.documentToDelete = documento;
            this.showDeleteModal = true;
        },

        async deleteDocument() {
            try {
                const response = await fetch(`{{ url('documentos') }}/${this.documentToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    this.documentos = this.documentos.filter(d => d.id !== this.documentToDelete.id);
                    this.filterDocuments();
                    this.showDeleteModal = false;
                    window.addActivity(`eliminó documento ${this.documentToDelete.title}`);
                    alert(data.message);
                } else {
                    alert('Error: ' + (data.error || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de conexión');
            }
        }
    }));
});
</script>
@endsection
