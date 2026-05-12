<?php

namespace Modules\User\Repositories;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\User\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRepo implements InterfaceUser
{
    public function findUserById($id)
    {
        return User::findOrFail($id);
    }
    public function findRoleById($id)
    {
        return Role::findOrFail($id);
    }
    public function getUsers()
    {
        return User::all();
    }
    public function getPermissions()
    {
        return Permission::all();
    }

    public function getRoles()
    {
        return Role::all();
    }

    public function createRole(array $data)
    {
        DB::beginTransaction();
        try {
            $role = Role::create(['name' => $data['name'],]);
            $permissions = $data['permissions'];
            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission['id'] ?? $permission);
            }
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Role created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function updateRole($id, array $data)
    {
        DB::beginTransaction();
        try {
            $role = Role::find($id);
            $role->update(['name' => $data['name']]);
            $permissions = $data['permissions'];
            foreach ($permissions as $permission) {
                $role->syncPermissions($permission['id'] ?? $permission);
            }
            DB::commit();
            return response()->json(['status' => true, 'message' => 'Role updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }

    public function user_create(array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::create(['name' => $data['name']]);
            $user->assignRole($data['role']);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'User created successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }

    public function user_update($id , array $data)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);
            $user->update(['name' => $data['name']]);
            $user->syncRoles($data['role']);
            DB::commit();
            return response()->json(['status' => true, 'message' => 'User updated successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

    }

    public function role_delete($id)
    {
        Role::find($id)->delete();
    }

}
