<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create_spt',
            'approve_spt',
            'create_sppd',
            'manage_users',
            'view_reports',

        ];

        foreach($permissions as $permissions){
            Permission::create([
                'name' => $permissions
            ]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $operatorRole = Role::firstOrCreate(['name' => 'operator']);
        $atasanRole = Role::firstOrCreate(['name' => 'atasan']);
        $karyawanRole = Role::firstOrCreate(['name' => 'karyawan']);


        $adminRole->givePermissionTo(Permission::all());
        $operatorRole->givePermissionTo(['create_spt', 'create_sppd', 'view_reports']);
        $atasanRole->givePermissionTo(['approve_spt', 'view_reports']);
        $karyawanRole->givePermissionTo(['create_spt']);

        $admin = User::firstOrCreate([
            'nama' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567890',
            'nip' => null,
        ]);
        $admin->assignRole('admin');

        $operator = User::firstOrCreate([
            'nama' => 'Operator 1',
            'email' => 'operator@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567891',
            'nip' => null,
        ]);
        $operator->assignRole('operator');

        $atasan = User::firstOrCreate([
            'nama' => 'Atasan 1',
            'email' => 'atasan@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567892',
            'nip' => null,
        ]);
        $atasan->assignRole('atasan');

        $karyawan = User::firstOrCreate([
            'nama' => 'Karyawan 1',
            'email' => 'karyawan@example.com',
            'password' => Hash::make('password123'),
            'no_hp' => '081234567893',
            'nip' => '12345678',
        ]);
        $karyawan->assignRole('karyawan');
    }

    
}
