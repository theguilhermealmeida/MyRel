<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Passwords\PasswordReset;




class PasswordResetController extends Controller
{

   public function sendResetLinkEmail(Request $request)
   {
    $request->validate(['email' => 'required|email']);

    $status = Password::sendResetLink(
        $request->only('email')
    );

    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
   }

   public function showResetForm(Request $request, $token) 
   {
    return view('auth.passwordReset', ['token' => $token, 'email' => $request->email]);
   }

   public function showForgotForm(Request $request) 
   {
    return view('auth.passwordForgot');
   }
   public function showChangeForm(Request $request) 
   {
    if (!Auth::check() || Auth::user()->id != $request->id)
    {
        abort(403);
    }
    return view('auth.passwordChange');
   }

   public function reset(Request $request) 
   {
    if (Auth::check()) 
    {
        Auth::logout();
    };
    
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|confirmed|min:6',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));

            $user->save();

            // event(new PasswordReset($user));
        }
    );
    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : redirect()->back()->withErrors(['email' => [__($status)]]);
   }
}

