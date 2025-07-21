@extends('layouts.app')

@section('title', 'Gestión de Roles')

@section('content')
<div class="p-6" x-data="rolesManagement()">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-blue-100 to-blue-200 border-blue-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total de roles</p>
                        <p class="text-3xl font-bold text-gray-900" x-text="totalRoles"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-settings w-6 h-6 text-white"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 .73 2.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 .73 2.73l-.43.25a2 2 0 0 0-1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-.73-2.73l.22-.38a2 2 0 0 0-.73-2.73l-.15-.1a2 2 0 0 1-.73-2.73l.43-.25a2 2 0 0 0 1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-green-100 to-green-200 border-green-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Roles con usuarios</p>
                        <p class="text-3xl font-bold text-gray-900" x-text="rolesWithUsers"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-plus w-6 h-6 text-white"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-orange-100 to-orange-200 border-orange-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Promedio de usuarios por rol</p>
                        <p class="text-3xl font-bold text-gray-900" x-text="avgUsersPerRole"></p>
                    </div>
                    <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users-round w-6 h-6 text-white"><path d="M18 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="10" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Gestión de roles</h2>
                    <button @click="openCreateModal()" class="text-sm font-medium hover:underline" style="color: #023A91;">
                        Crear nuevo rol
                    </button>
                </div>
                <button @click="exportRoles()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-green-600 text-primary-foreground shadow hover:bg-green-700 h-9 px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download w-4 h-4 mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Exportar
                </button>
            </div>

            <div class="mb-4">
                <div class="relative max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="text"
                        placeholder="Buscar roles..."
                        x-model="searchTerm"
                        @input="filterRoles"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-10"
                    />
                </div>
            </div>
        </div>

        <!-- Roles Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Nombre</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Descripción</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Usuarios Asignados</th>
                        <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Acciones</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0" x-cloak>
                    <template x-for="role in currentRoles" :key="role.id">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle font-medium" x-text="role.name"></td>
                            <td class="p-4 align-middle" x-text="role.description"></td>
                            <td class="p-4 align-middle">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800" x-text="role.usersCount"></span>
                            </td>
                            <td class="p-4 align-middle text-right">
                                <div class="flex justify-end space-x-2">
                                    <button @click="openEditModal(role)" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0 text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    <button @click="openDeleteModal(role)" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0 text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="currentRoles.length === 0">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td colspan="4" class="p-4 align-middle text-center text-gray-500">No se encontraron roles.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
            <div class="text-sm text-gray-500" x-cloak>
                Mostrando datos del <span x-text="startEntry"></span> al <span x-text="endEntry"></span> de <span x-text="filteredRoles.length"></span> entradas
            </div>
            <div class="flex items-center space-x-2" x-cloak>
                <button
                    @click="previousPage()"
                    :disabled="contactsData=== 1"
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left w-4 h-4"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <template x-for="page in totalPages" :key="page">
                    <button
                        @click="goToPage(page)"
                        :class="{ 'bg-[#023A91] text-white': contactsData=== page, 'border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground': contactsData!== page }"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 w-9 p-0"
                        x-text="page"
                    ></button>
                </template>
                <button
                    @click="nextPage()"
                    :disabled="contactsData=== totalPages"
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right w-4 h-4"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Create Role Modal -->
    <div x-show="isCreateModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
        <div class="relative w-full max-w-md p-4 bg-white rounded-lg shadow-lg" @click.outside="isCreateModalOpen = false">
            <div class="flex items-center justify-between pb-4 mb-4 border-b">
                <h3 class="text-lg font-semibold">Crear Nuevo Rol</h3>
                <button @click="isCreateModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="createRole()" class="space-y-4">
                <div>
                    <label for="new-name" class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
                    <input type="text" id="new-name" x-model="newRole.name" placeholder="Ingrese el nombre del rol" required
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                </div>
                <div>
                    <label for="new-description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="new-description" x-model="newRole.description" placeholder="Ingrese una descripción para el rol" rows="3"
                              class="flex min-h-[60px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="isCreateModalOpen = false" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 mr-2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-[#023A91] text-primary-foreground shadow hover:bg-[#023A91]/90 h-9 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save w-4 h-4 mr-2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
        <div class="relative w-full max-w-md p-4 bg-white rounded-lg shadow-lg" @click.outside="isEditModalOpen = false">
            <div class="flex items-center justify-between pb-4 mb-4 border-b">
                <h3 class="text-lg font-semibold">Editar Rol</h3>
                <button @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveEditRole()" class="space-y-4">
                <div>
                    <label for="edit-name" class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
                    <input type="text" id="edit-name" x-model="editRole.name" required
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                </div>
                <div>
                    <label for="edit-description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="edit-description" x-model="editRole.description" rows="3"
                              class="flex min-h-[60px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm shadow-sm placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"></textarea>
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="isEditModalOpen = false" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-4 h-4 mr-2"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        Cancelar
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-[#023A91] text-primary-foreground shadow hover:bg-[#023A91]/90 h-9 px-4 py-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-save w-4 h-4 mr-2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="isDeleteModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
        <div class="relative w-full max-w-md p-4 bg-white rounded-lg shadow-lg" @click.outside="isDeleteModalOpen = false">
            <div class="flex items-center justify-between pb-4 mb-4 border-b">
                <h3 class="text-lg font-semibold">Confirmar Eliminación</h3>
                <button @click="isDeleteModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="space-y-4 py-4">
                <div class="bg-red-50 border border-red-200 rounded-md p-3">
                    <p class="text-red-800 text-sm font-medium">⚠️ Esta acción no se puede deshacer</p>
                </div>

                <p class="text-gray-700">
                    Para confirmar la eliminación del rol <strong><span x-text="selectedRole?.name"></span></strong>, escriba la siguiente
                    frase:
                </p>

                <div class="bg-gray-100 p-2 rounded text-center font-mono text-sm">
                    Eliminar rol <span x-text="selectedRole?.name"></span>
                </div>

                <div>
                    <label for="delete-confirm-text" class="block text-sm font-medium text-gray-700">Confirmación</label>
                    <input type="text" id="delete-confirm-text" x-model="deleteConfirmText"
                           :placeholder="`Eliminar rol ${selectedRole?.name}`"
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="isDeleteModalOpen = false" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        Cancelar
                    </button>
                    <button
                        @click="confirmDeleteRole()"
                        :disabled="deleteConfirmText !== `Eliminar rol ${selectedRole?.name}`"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-red-600 text-primary-foreground shadow hover:bg-red-700 h-9 px-4 py-2"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 w-4 h-4 mr-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('rolesManagement', () => ({

            filteredRoles: [],
            currentPage: {{ $contactsData?? 1  }},
            searchTerm: '{{ $searchTerm ?? 1}}',
            itemsPerPage: {{ $perPage ?? 1}},
            isCreateModalOpen: false,
            isEditModalOpen: false,
            isDeleteModalOpen: false,
            selectedRole: null,
            newRole: { name: '', description: '' },
            editRole: { id: null, name: '', description: '' },
            deleteConfirmText: '',

            init() {
                this.filterRoles();
            },

            get totalRoles() {
                return this.filteredRoles.length;
            },
            get rolesWithUsers() {
                return this.filteredRoles.filter(role => role.usersCount > 0).length;
            },
            get avgUsersPerRole() {
                const totalUsers = this.filteredRoles.reduce((sum, role) => sum + role.usersCount, 0);
                return this.filteredRoles.length > 0 ? Math.round(totalUsers / this.filteredRoles.length) : 0;
            },

            filterRoles() {
                this.filteredRoles = this.allRoles.filter(role => {
                    const matchesSearch = role.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                          role.description.toLowerCase().includes(this.searchTerm.toLowerCase());
                    return matchesSearch;
                });
                this.goToPage(1); // Reset to first page on filter change
            },

            get currentRoles() {
                const startIndex = (this.contactsData- 1) * this.itemsPerPage;
                const endIndex = startIndex + this.itemsPerPage;
                return this.filteredRoles.slice(startIndex, endIndex);
            },

            get totalPages() {
                return Math.ceil(this.filteredRoles.length / this.itemsPerPage);
            },

            get startEntry() {
                return this.filteredRoles.length > 0 ? (this.contactsData- 1) * this.itemsPerPage + 1 : 0;
            },

            get endEntry() {
                return Math.min(this.contactsData* this.itemsPerPage, this.filteredRoles.length);
            },

            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.contactsData= page;
                }
            },

            previousPage() {
                this.goToPage(this.contactsData- 1);
            },

            nextPage() {
                this.goToPage(this.contactsData+ 1);
            },

            openCreateModal() {
                this.newRole = { name: '', description: '' };
                this.isCreateModalOpen = true;
            },

            async createRole() {
                if (!this.newRole.name) {
                    alert('Por favor, ingrese el nombre del rol.');
                    return;
                }

                try {
                    const response = await fetch('{{ route('roles.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.newRole)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Simulate adding to local data for immediate UI update
                        const newId = Math.max(...this.allRoles.map(r => r.id)) + 1;
                        const roleToAdd = { ...this.newRole, id: newId, usersCount: 0 };
                        this.allRoles.push(roleToAdd);
                        window.addActivity(`creó rol ${this.newRole.name}`);
                        this.filterRoles(); // Re-filter to update table and pagination
                        this.isCreateModalOpen = false;
                        alert(data.message);
                    } else {
                        alert('Error al crear rol: ' + (data.message || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error de red o servidor.');
                }
            },

            openEditModal(role) {
                this.selectedRole = role;
                this.editRole = { ...role }; // Clone to avoid direct mutation
                this.isEditModalOpen = true;
            },

            async saveEditRole() {
                if (!this.editRole.name) {
                    alert('Por favor, ingrese el nombre del rol.');
                    return;
                }

                try {
                    const response = await fetch(`{{ url('roles') }}/${this.editRole.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.editRole)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Update local data
                        const index = this.allRoles.findIndex(r => r.id === this.editRole.id);
                        if (index !== -1) {
                            this.allRoles[index] = { ...this.editRole };
                        }
                        window.addActivity(`editó rol ${this.editRole.name}`);
                        this.filterRoles(); // Re-filter to update table
                        this.isEditModalOpen = false;
                        this.selectedRole = null;
                        alert(data.message);
                    } else {
                        alert('Error al actualizar rol: ' + (data.message || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error de red o servidor.');
                }
            },

            openDeleteModal(role) {
                this.selectedRole = role;
                this.deleteConfirmText = '';
                this.isDeleteModalOpen = true;
            },

            async confirmDeleteRole() {
                if (this.deleteConfirmText !== `Eliminar rol ${this.selectedRole?.name}`) {
                    alert(`Debe escribir exactamente: "Eliminar rol ${this.selectedRole?.name}"`);
                    return;
                }

                try {
                    const response = await fetch(`{{ url('roles') }}/${this.selectedRole.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.allRoles = this.allRoles.filter(role => role.id !== this.selectedRole.id);
                        window.addActivity(`eliminó rol ${this.selectedRole.name}`);
                        this.filterRoles(); // Re-filter to update table
                        this.isDeleteModalOpen = false;
                        this.selectedRole = null;
                        this.deleteConfirmText = '';
                        alert(data.message);
                    } else {
                        alert('Error al eliminar rol: ' + (data.message || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error de red o servidor.');
                }
            },

            exportRoles() {
                window.addActivity("exportó lista de roles");
                const headers = ["Nombre", "Descripción", "Usuarios Asignados"];
                const rows = this.filteredRoles.map(role => [
                    `"${role.name}"`,
                    `"${role.description}"`,
                    `"${role.usersCount}"`
                ]);

                let csvContent = "data:text/csv;charset=utf-8,"
                    + headers.join(",") + "\n"
                    + rows.map(e => e.join(",")).join("\n");

                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", `roles_${new Date().toISOString().split("T")[0]}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }));
    });
</script>
@endsection 