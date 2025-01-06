<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear roles con firstOrCreate
        $role1 = Role::firstOrCreate(['name' => 'admin']);
        $role2 = Role::firstOrCreate(['name' => 'manager']);
        $role3 = Role::firstOrCreate(['name' => 'sales_clerk']); // empleados de venta
        $role4 = Role::firstOrCreate(['name' => 'accounting']); // contador
        $role5 = Role::firstOrCreate(['name' => 'marketing']);  // marketing

        // Crear permisos con firstOrCreate
        $permission1 = Permission::firstOrCreate(['name' => 'admin.orders.index', 'guard_name' => 'web']);
        $permission2 = Permission::firstOrCreate(['name' => 'user.orders.index', 'guard_name' => 'web']);
        $permission21 = Permission::firstOrCreate(['name' => 'gestion-usuarios.index', 'guard_name' => 'web']);

        // Asignar permisos a roles
        $role1->givePermissionTo([$permission1, $permission21]);
        $role2->givePermissionTo([$permission1, $permission21]);
        $role3->givePermissionTo($permission1);
        $role4->givePermissionTo($permission2);
        $role5->givePermissionTo($permission2);

        // Crear usuario admin si no existe
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Correo único para identificar al usuario
            [
                'name' => 'Admin', // Nombre del usuario
                'password' => bcrypt('password'), // Asegúrate de usar bcrypt para la contraseña
            ]
        );

        $user->assignRole($role1);
        $user->givePermissionTo([$permission1, $permission21]);

        echo "Usuario admin creado con éxito.";
    }
}
