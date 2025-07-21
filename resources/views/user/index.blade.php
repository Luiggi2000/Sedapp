@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="p-6" x-data="usersManagement()">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-blue-100 to-blue-200 border-blue-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Total de usuarios</p>
                        <p class="text-3xl font-bold text-gray-900" x-text="totalUsers"></p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-users w-6 h-6 text-white"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-green-100 to-green-200 border-green-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Usuarios activos</p>
                        <p class="text-3xl font-bold text-gray-900" x-text="activeUsers"></p>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-check w-6 h-6 text-white"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-red-100 to-red-200 border-red-200">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Usuarios inactivos</p>
                        <p class="text-3xl font-bold text-gray-900" x-text="inactiveUsers"></p>
                    </div>
                    <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-user-x w-6 h-6 text-white"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="17" x2="22" y1="17" y2="12"/><line x1="22" x2="17" y1="17" y2="12"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Users Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">Gestión de usuarios</h2>
                    <button @click="openCreateModal()" class="text-sm font-medium hover:underline" style="color: #023A91;">
                        Crear nuevo usuario
                    </button>
                </div>
                <button @click="exportUsers()" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-green-600 text-primary-foreground shadow hover:bg-green-700 h-9 px-4 py-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-download w-4 h-4 mr-2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" x2="12" y1="15" y2="3"/></svg>
                    Exportar
                </button>
            </div>

            <div class="flex flex-col sm:flex-row items-center gap-4 mb-4">
                <div class="relative flex-1 w-full sm:w-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="text"
                        placeholder="Buscar usuarios..."
                        x-model="searchTerm"
                        @input="filterUsers"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-10"
                    />
                </div>
                <select x-model="filterRole" @change="filterUsers" class="flex h-9 w-full sm:w-auto rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                    <option value="all">Todos los roles</option>
                    <option value="Administrador">Administrador</option>
                    <option value="Editor">Editor</option>
                    <option value="Visualizador">Visualizador</option>
                </select>
                <select x-model="filterStatus" @change="filterUsers" class="flex h-9 w-full sm:w-auto rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                    <option value="all">Todos los estados</option>
                    <option value="Activo">Activo</option>
                    <option value="Inactivo">Inactivo</option>
                </select>
            </div>
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="[&_tr]:border-b">
                    <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Nombre</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Correo</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Rol</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Estado</th>
                        <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Último Acceso</th>
                        <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Acciones</th>
                    </tr>
                </thead>
                <tbody class="[&_tr:last-child]:border-0" x-cloak>
                    <template x-for="user in currentUsers" :key="user.id">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td class="p-4 align-middle font-medium" x-text="user.name"></td>
                            <td class="p-4 align-middle" x-text="user.email"></td>
                            <td class="p-4 align-middle" x-text="user.role"></td>
                            <td class="p-4 align-middle">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="{
                                          'bg-green-100 text-green-800': user.status === 'Activo',
                                          'bg-red-100 text-red-800': user.status === 'Inactivo'
                                      }"
                                      x-text="user.status">
                                </span>
                            </td>
                            <td class="p-4 align-middle" x-text="user.lastLogin || 'N/A'"></td>
                            <td class="p-4 align-middle text-right">
                                <div class="flex justify-end space-x-2">
                                    <button @click="openEditModal(user)" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0 text-blue-600 hover:text-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                    </button>
                                    <button @click="openDeleteModal(user)" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0 text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                    <template x-if="currentUsers.length === 0">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <td colspan="6" class="p-4 align-middle text-center text-gray-500">No se encontraron usuarios.</td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
            <div class="text-sm text-gray-500" x-cloak>
                Mostrando datos del <span x-text="startEntry"></span> al <span x-text="endEntry"></span> de <span x-text="filteredUsers.length"></span> entradas
            </div>
            <div class="flex items-center space-x-2" x-cloak>
                <button
                    @click="previousPage()"
                    :disabled="currentPage === 1"
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-left w-4 h-4"><path d="m15 18-6-6 6-6"/></svg>
                </button>
                <template x-for="page in totalPages" :key="page">
                    <button
                        @click="goToPage(page)"
                        :class="{ 'bg-[#023A91] text-white': currentPage === page, 'border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground': currentPage !== page }"
                        class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 h-9 px-4 py-2 w-9 p-0"
                        x-text="page"
                    ></button>
                </template>
                <button
                    @click="nextPage()"
                    :disabled="currentPage === totalPages"
                    class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-chevron-right w-4 h-4"><path d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    <div x-show="isCreateModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
        <div class="relative w-full max-w-md p-4 bg-white rounded-lg shadow-lg" @click.outside="isCreateModalOpen = false">
            <div class="flex items-center justify-between pb-4 mb-4 border-b">
                <h3 class="text-lg font-semibold">Crear Nuevo Usuario</h3>
                <button @click="isCreateModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="createUser()" class="space-y-4">
                <div>
                    <label for="new-name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="new-name" x-model="newUser.name" placeholder="Ingrese el nombre del usuario" required
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                </div>
                <div>
                    <label for="new-email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="new-email" x-model="newUser.email" placeholder="Ingrese el correo electrónico" required
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                </div>
                <div>
                    <label for="new-role" class="block text-sm font-medium text-gray-700">Rol</label>
                    <select id="new-role" x-model="newUser.role" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                        <option value="Administrador">Administrador</option>
                        <option value="Editor">Editor</option>
                        <option value="Visualizador">Visualizador</option>
                    </select>
                </div>
                <div>
                    <label for="new-status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select id="new-status" x-model="newUser.status" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
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

    <!-- Edit User Modal -->
    <div x-show="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
        <div class="relative w-full max-w-md p-4 bg-white rounded-lg shadow-lg" @click.outside="isEditModalOpen = false">
            <div class="flex items-center justify-between pb-4 mb-4 border-b">
                <h3 class="text-lg font-semibold">Editar Usuario</h3>
                <button @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <form @submit.prevent="saveEditUser()" class="space-y-4">
                <div>
                    <label for="edit-name" class="block text-sm font-medium text-gray-700">Nombre</label>
                    <input type="text" id="edit-name" x-model="editUser.name" required
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                </div>
                <div>
                    <label for="edit-email" class="block text-sm font-medium text-gray-700">Correo Electrónico</label>
                    <input type="email" id="edit-email" x-model="editUser.email" required
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                </div>
                <div>
                    <label for="edit-role" class="block text-sm font-medium text-gray-700">Rol</label>
                    <select id="edit-role" x-model="editUser.role" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                        <option value="Administrador">Administrador</option>
                        <option value="Editor">Editor</option>
                        <option value="Visualizador">Visualizador</option>
                    </select>
                </div>
                <div>
                    <label for="edit-status" class="block text-sm font-medium text-gray-700">Estado</label>
                    <select id="edit-status" x-model="editUser.status" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
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
                    Para confirmar la eliminación del usuario <strong><span x-text="selectedUser?.name"></span></strong>, escriba la siguiente
                    frase:
                </p>

                <div class="bg-gray-100 p-2 rounded text-center font-mono text-sm">
                    Eliminar usuario <span x-text="selectedUser?.name"></span>
                </div>

                <div>
                    <label for="delete-confirm-text" class="block text-sm font-medium text-gray-700">Confirmación</label>
                    <input type="text" id="delete-confirm-text" x-model="deleteConfirmText"
                           :placeholder="`Eliminar usuario ${selectedUser?.name}`"
                           class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" @click="isDeleteModalOpen = false" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                        Cancelar
                    </button>
                    <button
                        @click="confirmDeleteUser()"
                        :disabled="deleteConfirmText !== `Eliminar usuario ${selectedUser?.name}`"
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
        Alpine.data('usersManagement', () => ({
            allUsers: @json($users),
            filteredUsers: [],
            currentPage: {{ $currentPage ?? 1 }},
            searchTerm: '{{ $searchTerm ?? 1}}',
            filterRole: '{{ $filterRole ?? 1 }}',
            filterStatus: '{{ $filterStatus ?? 1}}',
            itemsPerPage: {{ $perPage ?? 1 }},
            isCreateModalOpen: false,
            isEditModalOpen: false,
            isDeleteModalOpen: false,
            selectedUser: null,
            newUser: { name: '', email: '', role: 'Visualizador', status: 'Activo' },
            editUser: { id: null, name: '', email: '', role: '', status: '' },
            deleteConfirmText: '',

            init() {
                this.filterUsers();
            },

            get totalUsers() {
                return this.filteredUsers.length;
            },
            get activeUsers() {
                return this.filteredUsers.filter(user => user.status === 'Activo').length;
            },
            get inactiveUsers() {
                return this.filteredUsers.filter(user => user.status === 'Inactivo').length;
            },

            filterUsers() {
                this.filteredUsers = this.allUsers.filter(user => {
                    const matchesSearch = user.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                          user.email.toLowerCase().includes(this.searchTerm.toLowerCase());
                    const matchesRole = this.filterRole === 'all' || user.role === this.filterRole;
                    const matchesStatus = this.filterStatus === 'all' || user.status === this.filterStatus;
                    return matchesSearch && matchesRole && matchesStatus;
                });
                this.goToPage(1); // Reset to first page on filter change
            },

            get currentUsers() {
                const startIndex = (this.currentPage - 1) * this.itemsPerPage;
                const endIndex = startIndex + this.itemsPerPage;
                return this.filteredUsers.slice(startIndex, endIndex);
            },

            get totalPages() {
                return Math.ceil(this.filteredUsers.length / this.itemsPerPage);
            },

            get startEntry() {
                return this.filteredUsers.length > 0 ? (this.currentPage - 1) * this.itemsPerPage + 1 : 0;
            },

            get endEntry() {
                return Math.min(this.currentPage * this.itemsPerPage, this.filteredUsers.length);
            },

            goToPage(page) {
                if (page >= 1 && page <= this.totalPages) {
                    this.currentPage = page;
                }
            },

            previousPage() {
                this.goToPage(this.currentPage - 1);
            },

            nextPage() {
                this.goToPage(this.currentPage + 1);
            },

            openCreateModal() {
                this.newUser = { name: '', email: '', role: 'Visualizador', status: 'Activo' };
                this.isCreateModalOpen = true;
            },

            async createUser() {
                if (!this.newUser.name || !this.newUser.email || !this.newUser.role || !this.newUser.status) {
                    alert('Por favor, complete todos los campos.');
                    return;
                }

                try {
                    const response = await fetch('{{ route('usuarios.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.newUser)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Simulate adding to local data for immediate UI update
                        const newId = Math.max(...this.allUsers.map(u => u.id)) + 1;
                        const userToAdd = { ...this.newUser, id: newId, lastLogin: null };
                        this.allUsers.push(userToAdd);
                        window.addActivity(`creó usuario ${this.newUser.name}`);
                        this.filterUsers(); // Re-filter to update table and pagination
                        this.isCreateModalOpen = false;
                        alert(data.message);
                    } else {
                        alert('Error al crear usuario: ' + (data.message || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error de red o servidor.');
                }
            },

            openEditModal(user) {
                this.selectedUser = user;
                this.editUser = { ...user }; // Clone to avoid direct mutation
                this.isEditModalOpen = true;
            },

            async saveEditUser() {
                if (!this.editUser.name || !this.editUser.email || !this.editUser.role || !this.editUser.status) {
                    alert('Por favor, complete todos los campos.');
                    return;
                }

                try {
                    const response = await fetch(`{{ url('usuarios') }}/${this.editUser.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(this.editUser)
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Update local data
                        const index = this.allUsers.findIndex(u => u.id === this.editUser.id);
                        if (index !== -1) {
                            this.allUsers[index] = { ...this.editUser };
                        }
                        window.addActivity(`editó usuario ${this.editUser.name}`);
                        this.filterUsers(); // Re-filter to update table
                        this.isEditModalOpen = false;
                        this.selectedUser = null;
                        alert(data.message);
                    } else {
                        alert('Error al actualizar usuario: ' + (data.message || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error de red o servidor.');
                }
            },

            openDeleteModal(user) {
                this.selectedUser = user;
                this.deleteConfirmText = '';
                this.isDeleteModalOpen = true;
            },

            async confirmDeleteUser() {
                if (this.deleteConfirmText !== `Eliminar usuario ${this.selectedUser?.name}`) {
                    alert(`Debe escribir exactamente: "Eliminar usuario ${this.selectedUser?.name}"`);
                    return;
                }

                try {
                    const response = await fetch(`{{ url('usuarios') }}/${this.selectedUser.id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        this.allUsers = this.allUsers.filter(user => user.id !== this.selectedUser.id);
                        window.addActivity(`eliminó usuario ${this.selectedUser.name}`);
                        this.filterUsers(); // Re-filter to update table
                        this.isDeleteModalOpen = false;
                        this.selectedUser = null;
                        this.deleteConfirmText = '';
                        alert(data.message);
                    } else {
                        alert('Error al eliminar usuario: ' + (data.message || 'Error desconocido'));
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('Error de red o servidor.');
                }
            },

            exportUsers() {
                window.addActivity("exportó lista de usuarios");
                const headers = ["Nombre", "Correo", "Rol", "Estado", "Último Acceso"];
                const rows = this.filteredUsers.map(user => [
                    `"${user.name}"`,
                    `"${user.email}"`,
                    `"${user.role}"`,
                    `"${user.status}"`,
                    `"${user.lastLogin || 'N/A'}"`
                ]);

                let csvContent = "data:text/csv;charset=utf-8,"
                    + headers.join(",") + "\n"
                    + rows.map(e => e.join(",")).join("\n");

                const encodedUri = encodeURI(csvContent);
                const link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", `usuarios_${new Date().toISOString().split("T")[0]}.csv`);
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
            }
        }));
    });
</script>
@endsection
