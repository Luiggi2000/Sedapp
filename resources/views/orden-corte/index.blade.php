    @extends('layouts.app')

    @section('title', 'Órdenes de Corte')

    @section('content')
    <div class="p-6" x-data="ordersManagement()">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Gestión de Órdenes de Corte</h1>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-blue-100 to-blue-200 border-blue-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Total de órdenes</p>
                            <p class="text-3xl font-bold text-gray-900" x-text="totalOrders"></p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-yellow-100 to-yellow-200 border-yellow-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Órdenes pendientes</p>
                            <p class="text-3xl font-bold text-gray-900" x-text="pendingOrders"></p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.3 2.647-1.3 3.412 0l5.001 8.5a1.5 1.5 0 01-1.292 2.25H4.548a1.5 1.5 0 01-1.292-2.25l5.001-8.5zM10 8a1 1 0 110-2 1 1 0 010 2zm0 2a1 1 0 100 2 1 1 0 000-2z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-orange-100 to-orange-200 border-orange-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Órdenes en proceso</p>
                            <p class="text-3xl font-bold text-gray-900" x-text="inProcessOrders"></p>
                        </div>
                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="rounded-lg shadow-sm border border-gray-200 bg-gradient-to-br from-green-100 to-green-200 border-green-200">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Órdenes completadas</p>
                            <p class="text-3xl font-bold text-gray-900" x-text="completedOrders"></p>
                        </div>
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Listado de Órdenes</h2>
                    <button class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 bg-blue-600 text-primary-foreground shadow hover:bg-blue-700 h-9 px-4 py-2"
                            onclick="window.addActivity('creó una nueva orden de corte')">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus w-4 h-4 mr-2"><path d="M12 5v14"/><path d="M5 12h14"/></svg>
                        Nueva Orden
                    </button>
                </div>
                <div class="relative max-w-md">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    <input
                        type="text"
                        placeholder="Buscar órdenes..."
                        x-model="searchTerm"
                        @input="filterOrders"
                        class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 pl-10"
                        onchange="window.addActivity('buscó órdenes: &quot;' + this.value + '&quot;')"
                    />
                </div>
            </div>

            <!-- Orders Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="[&_tr]:border-b">
                        <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">ID Orden</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Cliente</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Estado</th>
                            <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Fecha</th>
                            <th class="h-12 px-4 text-right align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="[&_tr:last-child]:border-0" x-cloak>
                        <template x-for="orden in currentOrders" :key="orden.id">
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td class="p-4 align-middle font-medium" x-text="orden.id"></td>
                                <td class="p-4 align-middle" x-text="orden.cliente"></td>
                                <td class="p-4 align-middle">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="{
                                            'bg-yellow-100 text-yellow-800': orden.estado === 'Pendiente',
                                            'bg-blue-100 text-blue-800': orden.estado === 'En Proceso',
                                            'bg-green-100 text-green-800': orden.estado === 'Completada'
                                        }"
                                        x-text="orden.estado">
                                    </span>
                                </td>
                                <td class="p-4 align-middle" x-text="orden.fecha"></td>
                                <td class="p-4 align-middle text-right">
                                    <div class="flex justify-end space-x-2">
                                        <button @click="openEditModal(orden)" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0 text-blue-600 hover:text-blue-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-edit w-4 h-4"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </button>
                                        <button @click="openDeleteModal(orden)" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2 w-9 p-0 text-red-600 hover:text-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2 w-4 h-4"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <template x-if="currentOrders.length === 0">
                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                <td colspan="5" class="p-4 align-middle text-center text-gray-500">No se encontraron órdenes.</td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between px-6 py-4 border-t border-gray-200">
                <div class="text-sm text-gray-500" x-cloak>
                    Mostrando datos del <span x-text="startEntry"></span> al <span x-text="endEntry"></span> de <span x-text="filteredOrders.length"></span> entradas
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

        <!-- Create Order Modal -->
        <div x-show="isCreateModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="relative w-full max-w-md p-4 bg-white rounded-lg shadow-lg" @click.outside="isCreateModalOpen = false">
                <div class="flex items-center justify-between pb-4 mb-4 border-b">
                    <h3 class="text-lg font-semibold">Crear Nueva Orden</h3>
                    <button @click="isCreateModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <form @submit.prevent="createOrder()" class="space-y-4">
                    <div>
                        <label for="new-id" class="block text-sm font-medium text-gray-700">ID de Orden</label>
                        <input type="text" id="new-id" x-model="newOrder.id" placeholder="Ingrese el ID de orden" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                    </div>
                    <div>
                        <label for="new-cliente" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <input type="text" id="new-cliente" x-model="newOrder.cliente" placeholder="Ingrese el nombre del cliente" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                    </div>
                    <div>
                        <label for="new-estado" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select id="new-estado" x-model="newOrder.estado" required
                                class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Proceso">En Proceso</option>
                            <option value="Completada">Completada</option>
                        </select>
                    </div>
                    <div>
                        <label for="new-fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" id="new-fecha" x-model="newOrder.fecha" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
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

        <!-- Edit Order Modal -->
        <div x-show="isEditModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak>
            <div class="relative w-full max-w-md p-4 bg-white rounded-lg shadow-lg" @click.outside="isEditModalOpen = false">
                <div class="flex items-center justify-between pb-4 mb-4 border-b">
                    <h3 class="text-lg font-semibold">Editar Orden</h3>
                    <button @click="isEditModalOpen = false" class="text-gray-400 hover:text-gray-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-x w-5 h-5"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
                <form @submit.prevent="saveEditOrder()" class="space-y-4">
                    <div>
                        <label for="edit-id" class="block text-sm font-medium text-gray-700">ID de Orden</label>
                        <input type="text" id="edit-id" x-model="editOrder.id" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                    </div>
                    <div>
                        <label for="edit-cliente" class="block text-sm font-medium text-gray-700">Cliente</label>
                        <input type="text" id="edit-cliente" x-model="editOrder.cliente" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                    </div>
                    <div>
                        <label for="edit-estado" class="block text-sm font-medium text-gray-700">Estado</label>
                        <select id="edit-estado" x-model="editOrder.estado" required
                                class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring">
                            <option value="Pendiente">Pendiente</option>
                            <option value="En Proceso">En Proceso</option>
                            <option value="Completada">Completada</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit-fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
                        <input type="date" id="edit-fecha" x-model="editOrder.fecha" required
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
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
                        Para confirmar la eliminación de la orden <strong><span x-text="selectedOrder?.id"></span></strong>, escriba la siguiente
                        frase:
                    </p>

                    <div class="bg-gray-100 p-2 rounded text-center font-mono text-sm">
                        Eliminar orden <span x-text="selectedOrder?.id"></span>
                    </div>

                    <div>
                        <label for="delete-confirm-text" class="block text-sm font-medium text-gray-700">Confirmación</label>
                        <input type="text" id="delete-confirm-text" x-model="deleteConfirmText"
                            :placeholder="`Eliminar orden ${selectedOrder?.id}`"
                            class="flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50" />
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="isDeleteModalOpen = false" class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium transition-colors focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:pointer-events-none disabled:opacity-50 border border-input bg-background shadow-sm hover:bg-accent hover:text-accent-foreground h-9 px-4 py-2">
                            Cancelar
                        </button>
                        <button
                            @click="confirmDeleteOrder()"
                            :disabled="deleteConfirmText !== `Eliminar orden ${selectedOrder?.id}`"
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
            Alpine.data('ordersManagement', () => ({
allOrders: @json($ordenCortes),
                filteredOrders: [],
                currentPage: {{ $currentPage??1 }},
                searchTerm: '{{ $searchTerm??1}}',
                filterStatus: '{{ $filterStatus??1 }}',
                filterZone: '{{ $filterZone??1 }}',
                itemsPerPage: {{ $perPage??1 }},
                isCreateModalOpen: false,
                isEditModalOpen: false,
                isDeleteModalOpen: false,
                selectedOrder: null,
                newOrder: { id: '', cliente: '', estado: 'Pendiente', fecha: '' },
                editOrder: { id: null, cliente: '', estado: '', fecha: '' },
                deleteConfirmText: '',

                init() {
                    this.filterOrders();
                },

                get totalOrders() {
                    return this.filteredOrders.length;
                },
                get pendingOrders() {
                    return this.filteredOrders.filter(order => order.estado === 'Pendiente').length;
                },
                get inProcessOrders() {
                    return this.filteredOrders.filter(order => order.estado === 'En Proceso').length;
                },
                get completedOrders() {
                    return this.filteredOrders.filter(order => order.estado === 'Completada').length;
                },

                filterOrders() {
                    this.filteredOrders = this.allOrders.filter(order => {
                        const matchesSearch = order.id.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                            order.cliente.toLowerCase().includes(this.searchTerm.toLowerCase());
                        const matchesStatus = this.filterStatus === 'all' || order.estado === this.filterStatus;
                        const matchesZone = this.filterZone === 'all' || order.zona === this.filterZone;
                        return matchesSearch && matchesStatus && matchesZone;
                    });
                    this.goToPage(1); // Reset to first page on filter change
                },

                get currentOrders() {
                    const startIndex = (this.currentPage - 1) * this.itemsPerPage;
                    const endIndex = startIndex + this.itemsPerPage;
                    return this.filteredOrders.slice(startIndex, endIndex);
                },

                get totalPages() {
                    return Math.ceil(this.filteredOrders.length / this.itemsPerPage);
                },

                get startEntry() {
                    return this.filteredOrders.length > 0 ? (this.currentPage - 1) * this.itemsPerPage + 1 : 0;
                },

                get endEntry() {
                    return Math.min(this.currentPage * this.itemsPerPage, this.filteredOrders.length);
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
                    this.newOrder = { id: '', cliente: '', estado: 'Pendiente', fecha: '' };
                    this.isCreateModalOpen = true;
                },

                async createOrder() {
                    if (!this.newOrder.id || !this.newOrder.cliente || !this.newOrder.fecha) {
                        alert('Por favor, complete todos los campos.');
                        return;
                    }

                    try {

                        
                            const response = await fetch('/fake-url', { // <- temporal    
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.newOrder)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Simulate adding to local data for immediate UI update
                            const newId = Math.max(...this.allOrders.map(o => o.id)) + 1;
                            const orderToAdd = { ...this.newOrder, id: newId };
                            this.allOrders.push(oerToAdd);
                            window.addActivity(`creó orden ${this.newOrder.id}`);
                            this.filterOrders(); // Re-filter to update table and pagination
                            this.isCreateModalOpen = false;
                            alert(data.message);
                        } else {
                            alert('Error al crear orden: ' + (data.message || 'Error desconocido'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error de red o servidor.');
                    }
                },

                openEditModal(orden) {
                    this.selectedOrder = orden;
                    this.editOrder = { ...orden }; // Clone to avoid direct mutation
                    this.isEditModalOpen = true;
                },

                async saveEditOrder() {
                    if (!this.editOrder.id || !this.editOrder.cliente || !this.editOrder.fecha) {
                        alert('Por favor, complete todos los campos.');
                        return;
                    }

                    try {
                        const response = await fetch(`{{ url('ordenes') }}/${this.editOrder.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.editOrder)
                        });

                        const data = await response.json();

                        if (response.ok) {
                            // Update local data
                            const index = this.allOrders.findIndex(o => o.id === this.editOrder.id);
                            if (index !== -1) {
                                this.allOrders[index] = { ...this.editOrder };
                            }
                            window.addActivity(`editó orden ${this.editOrder.id}`);
                            this.filterOrders(); // Re-filter to update table
                            this.isEditModalOpen = false;
                            this.selectedOrder = null;
                            alert(data.message);
                        } else {
                            alert('Error al actualizar orden: ' + (data.message || 'Error desconocido'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error de red o servidor.');
                    }
                },

                openDeleteModal(orden) {
                    this.selectedOrder = orden;
                    this.deleteConfirmText = '';
                    this.isDeleteModalOpen = true;
                },

                async confirmDeleteOrder() {
                    if (this.deleteConfirmText !== `Eliminar orden ${this.selectedOrder?.id}`) {
                        alert(`Debe escribir exactamente: "Eliminar orden ${this.selectedOrder?.id}"`);
                        return;
                    }

                    try {
                        const response = await fetch(`{{ url('ordenes') }}/${this.selectedOrder.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });

                        const data = await response.json();

                        if (response.ok) {
                            this.allOrders = this.allOrders.filter(order => order.id !== this.selectedOrder.id);
                            window.addActivity(`eliminó orden ${this.selectedOrder.id}`);
                            this.filterOrders(); // Re-filter to update table
                            this.isDeleteModalOpen = false;
                            this.selectedOrder = null;rd
                            this.deleteConfirmText = '';
                            alert(data.message);
                        } else {
                            alert('Error al eliminar orden: ' + (data.message || 'Error desconocido'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Error de red o servidor.');
                    }
                },

                exportOrders() {
                    window.addActivity("exportó lista de órdenes");
                    const headers = ["ID Orden", "Cliente", "Estado", "Fecha"];
                    const rows = this.filteredOrders.map(order => [
                        `"${order.id}"`,
                        `"${order.cliente}"`,
                        `"${order.estado}"`,
                        `"${order.fecha}"`
                    ]);

                    let csvContent = "data:text/csv;charset=utf-8,"
                        + headers.join(",") + "\n"
                        + rows.map(e => e.join(",")).join("\n");

                    const encodedUri = encodeURI(csvContent);
                    const link = document.createElement("a");
                    link.setAttribute("href", encodedUri);
                    link.setAttribute("download", `ordenes_${new Date().toISOString().split("T")[0]}.csv`);
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }));
        });
    </script>
    @endsection
