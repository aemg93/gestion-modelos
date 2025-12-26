<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles
        $sAdmin = Role::firstOrCreate(['name' => 'S-Admin']);
        $admin  = Role::firstOrCreate(['name' => 'Admin']);
        $modelo = Role::firstOrCreate(['name' => 'Modelo']);

        // Ejemplo de permisos
        $permissions = [
            'create models',
            'edit models',
            'delete models',
            'register earnings',
            'edit earnings',
            'create platforms',
        ];

        foreach ($permissions as $perm) {
            $permission = Permission::firstOrCreate(['name' => $perm]);
            $sAdmin->givePermissionTo($permission);
        }

        // Admin solo algunos permisos
        $admin->givePermissionTo(['create models', 'register earnings', 'create platforms']);

        // Modelo solo registrar ganancias
        $modelo->givePermissionTo(['register earnings']);
    }
}
