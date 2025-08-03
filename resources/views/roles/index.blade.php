@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="p-6" x-data="rolesData()">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold" style="color: #023A91">Gestión de Roles</h1>
            <p class="text-gray-600">Administra los roles del sistema</p>
        </div>
        <button @click="openCreateModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nuevo Rol
        </button>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-lg shadow mb-6 p-4">
        <input 
            type="text" 
            x-model="searchTerm" 
            @input="filterRoles()"
            placeholder="Buscar roles..." 
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
    </div>

    <!-- Roles Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guard Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha Creación</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <template x-for="role in filteredRoles" :key="role.id">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900" x-text="role.name"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="role.guard_name"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900" x-text="new Date(role.created_at).toLocaleDateString()"></td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="openEditModal(role)" class="text-indigo-600 hover:text-indigo-900 mr-3">Editar</button>
                                <button @click="confirmDelete(role)" class="text-red-600 hover:text-red-900">Eliminar</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $roles->links() }}
    </div>

    <!-- Create/Edit Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4" x-text="editingRole ? 'Editar Rol' : 'Crear Rol'"></h3>
                <form @submit.prevent="submitForm()">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input type="text" x-model="form.name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Guard Name</label>
                        <input type="text" x-model="form.guard_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" @click="closeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancelar</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <span x-text="editingRole ? 'Actualizar' : 'Crear'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('rolesData', () => ({
        roles: @json($roles->items()),
        filteredRoles: [],
        currentPage: 1,
        searchTerm: '',
        itemsPerPage: 10,
        showModal: false,
        editingRole: null,
        form: { name: '', guard_name: 'web' },
        selectedRole: null,

        init() {
            this.filterRoles();
        },

        filterRoles() {
            this.filteredRoles = this.roles.filter(role => {
                const matchesSearch = role.name.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                                      role.guard_name.toLowerCase().includes(this.searchTerm.toLowerCase());
                return matchesSearch;
            });
        },

        openCreateModal() {
            this.form = { name: '', guard_name: 'web' };
            this.editingRole = null;
            this.showModal = true;
        },

        openEditModal(role) {
            this.form = { ...role };
            this.editingRole = role;
            this.selectedRole = role;
            this.showModal = true;
        },

        closeModal() {
            this.showModal = false;
            this.editingRole = null;
            this.selectedRole = null;
        },

        async submitForm() {
            if (!this.form.name || !this.form.guard_name) {
                alert('Por favor, complete todos los campos.');
                return;
            }

            try {
                const method = this.editingRole ? 'PUT' : 'POST';
                const url = this.editingRole ? `{{ url('roles') }}/${this.selectedRole.id}` : '{{ route('roles.store') }}';

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
                    if (this.editingRole) {
                        const index = this.roles.findIndex(r => r.id === this.selectedRole.id);
                        if (index !== -1) {
                            this.roles[index] = { ...this.form, id: this.selectedRole.id };
                        }
                        window.addActivity(`editó rol ${this.form.name}`);
                    } else {
                        const newId = Math.max(...this.roles.map(r => r.id)) + 1;
                        const roleToAdd = { ...this.form, id: newId, created_at: new Date().toISOString() };
                        this.roles.push(roleToAdd);
                        window.addActivity(`creó rol ${this.form.name}`);
                    }
                    this.filterRoles();
                    this.closeModal();
                    alert(data.message || 'Operación exitosa');
                } else {
                    alert('Error al procesar el rol: ' + (data.message || 'Error desconocido'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error de red o servidor.');
            }
        },

        confirmDelete(role) {
            if (confirm(`¿Estás seguro de que quieres eliminar el rol ${role.name}?`)) {
                this.selectedRole = role;
                this.deleteRole();
            }
        },

        async deleteRole() {
            try {
                const response = await fetch(`{{ url('roles') }}/${this.selectedRole.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (response.ok) {
                    this.roles = this.roles.filter(role => role.id !== this.selectedRole.id);
                    window.addActivity(`eliminó rol ${this.selectedRole.name}`);
                    this.filterRoles();
                    this.closeModal();
                    alert(data.message || 'Rol eliminado exitosamente');
                } else {
                    alert('Error al eliminar rol: ' + (data.message || 'Error desconocido'));
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