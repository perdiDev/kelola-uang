<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['slug' => 'user.view',   'label' => 'Lihat User'],
            ['slug' => 'user.create', 'label' => 'Tambah User'],
            ['slug' => 'user.edit',   'label' => 'Edit User'],
            ['slug' => 'user.delete', 'label' => 'Hapus User'],

            // Role
            ['slug' => 'role.view',   'label' => 'Lihat Role'],
            ['slug' => 'role.create', 'label' => 'Tambah Role'],
            ['slug' => 'role.edit',   'label' => 'Edit Role'],
            ['slug' => 'role.delete', 'label' => 'Hapus Role'],

            // Permission
            ['slug' => 'permission.view',   'label' => 'Lihat Permission'],
            ['slug' => 'permission.create', 'label' => 'Tambah Permission'],
            ['slug' => 'permission.edit',   'label' => 'Edit Permission'],
            ['slug' => 'permission.delete', 'label' => 'Hapus Permission'],
        ];

        Permission::insert($permissions);
    }
}
