<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'g-recaptcha-response' => ['required', 'captcha'],
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists
        if (! $user) {
            return back()->withErrors([
                'email' => 'No account found with that email address.',
            ])->withInput($request->only('email'));
        }

        // Check if user is activated
        if (! $user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is not activated. Please contact the administrator.',
            ])->withInput($request->only('email'));
        }

        // Check if user has the 'admin' role
        if (! $user->hasRole('Admin')) {
            return back()->withErrors([
                'email' => 'Only admin accounts are allowed to reset the password here.',
            ])->withInput($request->only('email'));
        }

        // Send reset link
        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)])
                    ->withInput($request->only('email'));
    }
}
