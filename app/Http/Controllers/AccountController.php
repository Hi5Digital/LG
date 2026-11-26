<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        return view('account', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $emailChanged = $validated['email'] !== $user->email;
        $passwordChanged = $request->filled('password');

        if ($emailChanged || $passwordChanged) {
            if (! $request->filled('current_password') || ! Hash::check($request->input('current_password'), $user->password)) {
                return back()->withInput($request->except('current_password', 'password', 'password_confirmation'))
                    ->withErrors(['current_password' => 'The password is incorrect.']);
            }
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($passwordChanged) {
            $user->password = Hash::make($validated['password']);
        }
        $user->save();

        if ($passwordChanged) {
            $request->session()->put('password_hash_web', $user->getAuthPassword());
        }

        if ($passwordChanged) {
            return back()->with('success', 'Password updated. Other browsers will need to sign in again.');
        }

        return back()->with('success', 'Account updated');
    }
}
