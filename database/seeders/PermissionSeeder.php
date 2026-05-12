<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::updateOrCreate(['name' => 'factor-list']);
        Permission::updateOrCreate(['name' => 'factor-insert']);

        Permission::updateOrCreate(['name' => 'category-list']);

        Permission::updateOrCreate(['name' => 'sms-panel']);

        Permission::updateOrCreate(['name' => 'list_stores']);
        Permission::updateOrCreate(['name' => 'create_store']);

        Permission::updateOrCreate(['name' => 'list_tickets']);
        Permission::updateOrCreate(['name' => 'insert_ticket']);

        Permission::updateOrCreate(['name' => 'user-insert']);
        Permission::updateOrCreate(['name' => 'user-list']);

        Permission::updateOrCreate(['name' => 'role-list']);
        Permission::updateOrCreate(['name' => 'role-insert']);
    }
}
