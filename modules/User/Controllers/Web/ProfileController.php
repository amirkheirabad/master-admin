<?php

namespace Modules\User\Controllers\Web;

use Modules\User\Repositories\InterfaceUser;
use Modules\User\Requests\UpdatePasswordRequest;

class ProfileController
{
    private InterfaceUser $user;

    public function __construct(InterfaceUser $user)
    {
        $this->user = $user;
    }

    public function show()
    {
        $user = auth()->user();

        return view('templates.user.profile', compact('user'));
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $result = $this->user->updatePassword(auth()->id(), $request->validated()['password']);

        if ($result === true) {
            return response()->json([
                'success' => true,
                'redirect' => route('dashboard'),
            ]);
        }

        return response()->json([
            'success' => false,
            'errors' => ['general' => [$result]],
        ], 422);
    }
}
