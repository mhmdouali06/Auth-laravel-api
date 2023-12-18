<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role ::create(['name' => 'admin']);
        $editorRole = Role::create(['name' => 'editor']);

        // Create permissions
        $editArticlesPermission = Permission::create(['name' => 'edit articles']);
        $deleteArticlesPermission = Permission::create(['name' => 'delete articles']);

        // Assign permissions to roles
        $adminRole->givePermissionTo($editArticlesPermission, $deleteArticlesPermission);
        $editorRole->givePermissionTo($editArticlesPermission);
        // User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
