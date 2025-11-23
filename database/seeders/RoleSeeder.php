<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $owner = Role::create([
            'slug' => 'owner',
            'label' => 'Pemilik Sistem',
        ]);

        $user = Role::create([
            'slug' => 'user',
            'label' => 'Pengguna Biasa',
        ]);

        // Owner full access
        $owner->permissions()->sync(
            Permission::pluck('id')->toArray()
        );

        // User → no access (default)
        $user->permissions()->sync(
            Permission::where('slug', 'user.view')->pluck('id')
        );
    }
}
