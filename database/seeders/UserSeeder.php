<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::create([
            'name' => 'Owner',
            'email' => 'owner@example.com',
        ]);

        $owner->roles()->syncWithoutDetaching([1]); // owner

        $kasir = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@example.com',
        ]);

        $kasir->roles()->syncWithoutDetaching([2]); // user biasa
    }
}
