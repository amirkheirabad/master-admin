<?php

namespace Modules\User\Controllers\Web;

use Illuminate\Http\Request;
use Modules\User\Repositories\InterfaceUser;
use Modules\User\Requests\InsertRoleRequest;
use Modules\User\Requests\InsertUserRequest;

class UserController
{
    private InterfaceUser $user;

    public function __construct(InterfaceUser $user)
    {
        $this->user = $user;
    }

    public function index(Request $request)
    {
        $users = $this->user->filterUsers($request);
        $roles = $this->user->getRoles();

        return view('templates.user.list', compact('users', 'roles'));
    }

    public function insert()
    {
        $roles = $this->user->getRoles();

        return view('templates.user.insert', compact('roles'));
    }

    public function edit($id)
    {
        $user = $this->user->findUserById($id);
        $roles = $this->user->getRoles();

        return view('templates.user.edit', compact('user', 'roles'));
    }

    // اپدیت
    public function update($id, InsertUserRequest $request)
    {
        $user = $this->user->findUserById($id);
        $data = $request->validated();

        // اگر شماره عوض نشده بود، قانون unique رو حذف کن
        if ($user->mobile === $data['mobile']) {
            // حذف قانون unique برای این درخواست
            $rules = $request->rules();
            $rules['mobile'] = 'required';
            $request->validate($rules);
        }

        $result = $this->user->user_update($id, $data);

        if ($result === true) {
            return response()->json([
                'success' => true,
                'redirect' => route('user-list'),
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => ['general' => [$result]],
        ], 422);
    }

    public function destroy($id)
    {
        $result = $this->user->user_delete($id);

        if ($result === true) {
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => $result], 422);
    }

    public function role_insert()
    {
        $permissions = $this->user->getPermissions();

        return view('templates.user.role-insert', compact('permissions'));
    }

    public function role_create(InsertRoleRequest $request)
    {
        $this->user->createRole($request->validated());

        return response()->json([
            'success' => true,
            'redirect' => route('role-list'),
        ]);
    }

    public function role_list()
    {
        $roles = $this->user->getRoles();

        return view('templates.user.role-list', compact('roles'));
    }

    public function user_create(InsertUserRequest $request)
    {
        $result = $this->user->user_create($request->validated());
        if ($result === true) {

            return response()->json([
                'success' => true,
                'redirect' => route('user-list'),
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => ['general' => [$result]],
        ], 422);
    }

    public function role_edit($id)
    {
        $permissions = $this->user->getPermissions();
        $role = $this->user->findRoleById($id);

        return view('templates.user.role-edit', compact('role', 'permissions'));
    }

    public function role_update($id, InsertRoleRequest $request)
    {
        $this->user->updateRole($id, $request->validated());

        return response()->json([
            'success' => true,
            'redirect' => route('role-list'),
        ]);
    }

    public function role_delete($id)
    {
        $this->user->role_delete($id);
    }
}
