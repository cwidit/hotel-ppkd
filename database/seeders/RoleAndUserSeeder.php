<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset Spatie permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create roles
        $adminRole = Role::create(['name' => 'Administrator']);
        $foRole = Role::create(['name' => 'Front Office']);
        $hkRole = Role::create(['name' => 'Housekeeping']);
        $fnbRole = Role::create(['name' => 'Food & Beverage']);

        // Create Admin user
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@hotel.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole($adminRole);
        
        // Create dummy FO
        $fo = User::create([
            'name' => 'Front Office Staff',
            'email' => 'fo@hotel.com',
            'password' => Hash::make('password123'),
        ]);
        $fo->assignRole($foRole);
        
        // Create dummy HK
        $hk = User::create([
            'name' => 'Housekeeping Staff',
            'email' => 'hk@hotel.com',
            'password' => Hash::make('password123'),
        ]);
        $hk->assignRole($hkRole);
        
        // Create dummy FnB
        $fnb = User::create([
            'name' => 'FnB Staff',
            'email' => 'fnb@hotel.com',
            'password' => Hash::make('password123'),
        ]);
        $fnb->assignRole($fnbRole);
    }
}
