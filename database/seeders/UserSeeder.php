<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'kasir']);
        $kasir = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@gmail.com',
            'password' => bcrypt('kasir123'),
        ]);
        $kasir->assignRole('kasir');
        $kasir->givePermissionTo('kasir');

        Permission::create(['name' => 'produksi']);
        $produksi = User::create([
            'name' => 'Operator Produksi',
            'email' => 'produksi@gmail.com',
            'password' => bcrypt('produksi123'),
        ]);
        $produksi->assignRole('produksi');
        $produksi->givePermissionTo('produksi');

        Permission::create(['name' => 'manager']);
        $manager = User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => bcrypt('manager123'),
        ]);
        $manager->assignRole('manager');
        $manager->givePermissionTo('manager');
    }
}
