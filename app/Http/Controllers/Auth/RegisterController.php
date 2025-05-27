<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pembeli;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'unique:pembelis'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:pembelis'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $pembeli = Pembeli::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('pembeli')->login($pembeli);

        return redirect('/');
    }
}