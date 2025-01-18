<?php

namespace App\Http\Controllers\Auth;

use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    //
    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:6',
        ]);

        User::create($credentials);

        $name = $credentials['name'];
        $email = $credentials['email'];
        $hash = sha1($email);

        $verificationUrl = URL::temporarySignedRoute(
            'verify.email',
            now()->addMinutes(10),
            ['email' => $email, 'hash' => $hash],
        );

        Mail::to($email)->send(new RegisterMail($name, $email, $hash, $verificationUrl));
        
        return redirect()->route('login')->with('success', 'Registeration successful. Login now');
    }

    public function auth_login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('home');
        }
    }
}
