<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/');
        }

        return back()->withErrors(['message' => 'Invalid credentials']);
    }

    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'username' => 'required|string|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'location' => 'required|string|max:255',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'username' => $request->username,
        'password' => Hash::make($request->password),
        'location' => $request->location,
    ]);

    Auth::login($user);

    return redirect('/');
}
    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }

    public function showProfile()
    {
        return view('profile', ['user' => Auth::user()]);
    }
    
    public function updateProfile(Request $request)
    {
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username,' . auth()->id(),
        'location' => 'required|string|max:255',
    ]);

    $user = auth()->user();
    $user->name = $request->name;
    $user->username = $request->username;
    $user->location = $request->location;
    $user->save();

    return redirect()->route('profile')->with('success', 'Profile updated successfully.');
}

public function deleteAccount()
{
    $user = auth()->user();
    auth()->logout();
    $user->delete();

    return response()->json(['success' => true]);
}
}
