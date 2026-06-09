<?php

namespace Modules\User\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\User\Repositories\InterfaceAuth;
use Modules\User\Requests\LoginRequest;

class AuthController extends Controller
{
    private InterfaceAuth $authRepo;

    public function __construct(InterfaceAuth $authRepo)
    {
        $this->authRepo = $authRepo;
    }

    public function showLoginForm()
    {
        if ($this->authRepo->getAuthenticatedUser()) {
            return redirect($this->authRepo->getRedirectUrlByRole());
        }

        return view('templates.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $request->validate([
            'mobile' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'mobile' => $request->mobile,
            'password' => $request->password,
        ];

        if ($this->authRepo->attemptLogin($credentials, $request->has('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended($this->authRepo->getRedirectUrlByRole());
        }

        return back()->withErrors([
            'mobile' => 'شماره موبایل یا رمز عبور اشتباه است.',
        ])->onlyInput('mobile');
    }

    public function logout(Request $request)
    {
        $this->authRepo->logout();
        return redirect('/login');
    }
}
