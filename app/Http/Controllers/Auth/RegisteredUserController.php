<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): mixed
    {
        $request->validate([
        'name' => ['required', 'string', 'max:255', 'regex:/^[A-Za-zÑñ\s\-.]+$/'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        'g-recaptcha-response' => ['required', 'captcha'],
        'password' => [
            'required',
            'confirmed',
            Password::min(8)
                ->mixedCase()  // Requires at least one upper & one lower case letter
                ->numbers()    // Requires at least one number
                ->symbols(),   // Requires at least one special character
        ],
    ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->assignRole('User');
        event(new Registered($user));

        if (Auth::check()) {
            return redirect()->route('users')->with('message', 'User registered successfully, Wait for activation.');
        } 

        return redirect()->route('register')->with('message', 'User registered successfully, Wait for activation.');

    }
   

}
