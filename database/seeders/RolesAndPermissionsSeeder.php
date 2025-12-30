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

        // Definir permisos
        $permissions = [
            'register earnings',    // registrar ganancias
            'view own profile',     // ver su propio perfil
            'view all profiles',    // ver todos los perfiles
            'view work hours',      // ver horas trabajadas/faltantes
            'create models',        // crear modelos
            'edit models',          // editar modelos
            'delete models',        // eliminar modelos
            'create platforms',     // crear plataformas
            'manage platforms',     // administrar plataformas
            'edit earnings',        // editar ganancias
            'delete earnings',      // eliminar ganancias
        ];

        // Crear permisos en BD si no existen
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Asignación de permisos a Modelo
        $modelo->syncPermissions([
            'register earnings',
            'view own profile',
        ]);

        // Asignación de permisos a Admin
        $admin->syncPermissions([
            'register earnings',   // registrar ganancias de modelos
            'view all profiles',   // ver todos los perfiles
            'view work hours',     // ver horas trabajadas/faltantes
            'create models',       // crear modelos
            'create platforms',    // crear plataformas
            'manage platforms',    // administrar plataformas
        ]);

        // Asignación de permisos a S-Admin (todos)
        $sAdmin->syncPermissions(Permission::all());
    }
}
