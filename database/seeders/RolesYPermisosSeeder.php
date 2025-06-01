<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesYPermisosSeeder extends Seeder
{
    public function run(): void
    {
        // 🔄 Limpiar caché de permisos
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // ✅ Lista de permisos que deseas crear
        $permisosDeseados = [
            'ver roles',
            'crear roles',
            'editar roles',
            'eliminar roles',
        ];

        // 🧼 Elimina permisos que no estén en la lista (opcional)
        Permission::whereNotIn('name', $permisosDeseados)->delete();

        // 🛠️ Crear permisos si no existen
        foreach ($permisosDeseados as $permiso) {
            Permission::firstOrCreate(['name' => $permiso]);
        }

        // 👤 Crear rol admin si no existe
        $admin = Role::firstOrCreate(['name' => 'admin']);

        // 🔗 Asignar permisos al rol admin
        $admin->syncPermissions($permisosDeseados); // Usa nombres directamente

        // ✅ Mostrar en consola qué se hizo (solo para debugging)
        $this->command->info('Permisos asignados al rol admin:');
        foreach ($admin->permissions as $permiso) {
            $this->command->info("- {$permiso->name}");
        }
    }
}
