<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seller', 'guard_name' => 'web']);

        $admin = User::create([
            'name' => 'سیناس',
            'mobile' => '09111111111',
            'password' => Hash::make('123456'),
        ]);

        $admin->assignRole('admin');

        $seller = User::create([
            'name' => 'ممد',
            'mobile' => '09222222222',
            'password' => Hash::make('123456'),
        ]);

        $seller->assignRole('seller');
    }
}