<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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

    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search', '');
        $authUserId = auth()->id(); 
    
        $query = User::with('roles')
            ->where('id', '!=', $authUserId) 
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            });
    
        $users = $query->paginate($perPage);
    
        return response()->json($users);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'is_active' => 'required|boolean',
            'role' => 'required|in:admin,user',
        ]);
    
        $user = User::findOrFail($request->id);
    
        if (auth()->id() === $user->id && $request->role !== $user->getRoleNames()->first()) {
            return back()->withErrors(['You cannot change your own role.']);
        }
    
        $updated = false;
    
        if ((int) $user->is_active !== (int) $request->is_active) {
            $user->is_active = $request->is_active;
            $updated = true;
        }
    
        $currentRole = strtolower($user->getRoleNames()->first() ?? '');
        if ($currentRole !== strtolower($request->role)) {
            $user->syncRoles([$request->role]);
            $updated = true;
        }
    
        if ($updated) {
            $user->save();
            return back()->with('message', 'User status and role updated successfully.');
        }
    
        return back()->with('message', 'No changes detected.');
    }
    
    public function destroy($id)
    {
        if (auth()->id() === $id) {
            return response()->json(['message' => 'You cannot delete your own account.']);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }
}
