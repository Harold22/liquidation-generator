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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
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

        $user->is_active = $request->is_active;
        $user->save();

        $user->syncRoles([$request->role]);

        return back()->with('message', 'User status and role updated successfully.');
    }
    public function destroy($id)
    {
        if (auth()->id() === $id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 403);
        }

        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.'], 200);
    }
}
