<?php

namespace App\Livewire\Auth;

use App\Mail\VerifyMail;
use App\Models\User;
use Livewire\Component;
use Masmerise\Toaster\Toaster;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class LoginForm extends Component
{
    public $email;

    public $password;

    public function login()
    {
        $validate = $this->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $validate['email'])->first();

        if(!$user) {
            Toaster::error('Email does not exist.');

            $this->reset(['password']);

            return;
        }

        if (is_null($user->email_verified_at)) {

            $this->reset(['password']);

            $name = $user->name;

            $email = $user->email;

            $hash = sha1($email);

            $verificationUrl = URL::temporarySignedRoute(
                'verify.email',
                now()->addMinutes(10),
                ['email' => $email, 'hash' => $hash],
            );

            Mail::to($user->email)->send(new VerifyMail($name, $email, $hash, $verificationUrl));

            Toaster::error('We noticed that your email is not verified. We sent another link. Please verify your email address');

            return;
        }

        if (!Hash::check($validate['password'], $user->password)) {
            Toaster::error('The password is not correct.');

            $this->reset(['password']);

            return;
        }

        Auth::login($user);

        Redirect::route('home')->success('Welcome back ' . $user->name);
    }

    public function render()
    {
        return view('livewire.auth.login-form');
    }
}
