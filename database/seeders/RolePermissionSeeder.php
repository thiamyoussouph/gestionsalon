<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        
        // Créer des permissions
        Permission::create(['name' => 'edit profile']);
        Permission::create(['name' => 'delete user']);
        Permission::create(['name' => 'add user']);

        // Créer des rôles et assigner des permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(['edit profile', 'delete user', 'add user']);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo(['edit profile']);
    }
}
