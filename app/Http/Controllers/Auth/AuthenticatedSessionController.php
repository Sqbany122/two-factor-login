<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    public function twoFactor() {
        return view('auth.two-factor');
    }

    public function twoFactorCheck(Request $request) {
        $request->validate([
            'two_factor' => 'string|required'
        ]);

        if ($request->two_factor == '111111') {
            $request->session()->put('two_factor', true);
            return redirect()->intended(RouteServiceProvider::HOME);
        } else {
            throw ValidationException::withMessages([
                'two_factor' => 'Provided wrong two factor code'
            ]);
        }
    }

    public function checkLoginCredentials(LoginRequest $request) {
        $request->validate([
            'email' => 'email:rfc,dns'
        ]);

        $email = $request->email;
        $password = $request->password;

        if(Auth::attempt(['email' => $email,'password' => $password])) {
            if (Auth::user()->two_factor === 1) {
                return redirect()->route('two-factor');
            } else {
                $request->session()->regenerate();
                return redirect()->intended(RouteServiceProvider::HOME);
            }
        } else {
            $request->session()->regenerate();
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
