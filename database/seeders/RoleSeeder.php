<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // admin
        Role::create([
            'name' => 'kasir',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'produksi',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'manager',
            'guard_name' => 'web'
        ]);

        Role::create([
            'name' => 'pelanggan',
            'guard_name' => 'web'
        ]);
    }
}
