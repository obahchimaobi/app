<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');

Route::post('/auth/register', [AuthController::class, 'store'])->name('auth.register');
Route::post('/auth/login', [AuthController::class, 'auth_login'])->name('auth.login');

Route::get('/logout', function () {
    if (!Auth::check()) {
        return Redirect::route('login')->error('You are not logged in.');
    }

    Auth::logout();

    return Redirect::route('home')->success('Logged out.');

})->name('logout');

Route::get('/email/verify/{email}/{hash}', function ($email, $hash) {
    $user = User::where('email', $email)->firstOrFail();

    if (! hash_equals(sha1($user->email), $hash)) {
        abort(403, 'Invalid verification link.');
    }

    if ($user->email_verified_at) {
        return Redirect::route('login')->error('Your email is already verified 😐');
    }

    // $user->update(['email_verified_at' => now()]);
    $user->email_verified_at = now();
    $user->save();

    return Redirect::route('login')->success('Your email has been verified 🥳');
})->middleware('signed')->name('verify.email');