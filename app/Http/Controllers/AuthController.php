<?php

namespace App\Http\Controllers;

use App\Mail\SendPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Laravel\Pail\ValueObjects\Origin\Console;
use Tymon\JWTAuth\Exceptions\JWTException;


class AuthController extends Controller
{




    public function register(Request $request)
    {
        $request->validate(config('validation.register'));

        $randomPassword = $this->generateRandomPassword(5);

        $user = User::create([
            'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
            'email' => $request->input('email'),
            'date_of_birth' => $request->input('date_of_birth'),
            'password' => Hash::make($randomPassword),
        ]);

        Mail::to($user->email)->send(new \App\Mail\SendPasswordMail($randomPassword));

        $token = JWTAuth::fromUser($user);

        return redirect()->route('login');
    }


    private function generateRandomPassword($length = 5)
    {
        $letters = 'abc';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()_+';

        $allCharacters = $letters . $numbers . $specialChars;

        return substr(str_shuffle(str_repeat($allCharacters, $length)), 0, $length);
    }




    public function login(Request $request)
    {
        $request->validate(config('validation.login'));

        if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
            return back()->withErrors([
                'password' => 'The provided credentials do not match our records.',
            ]);
        }


        $user = JWTAuth::user();

        Auth::login($user);

        session(['auth_token' => $token]);

        if (!$user->password_changed) {
            return redirect()->route('change_password.form');
        }

        return redirect()->route('welcome');
    }



    public function showChangePasswordForm()
    {

        $token = session('auth_token');


        if (!$token) {
            return redirect()->route('login')->withErrors(['message' => 'Please log in to access this page.']);
        }

        try {

            if (!$user = JWTAuth::setToken($token)->authenticate()) {
                return 'user not found';
            }
        } catch (JWTException $e) {
            return 'token not found';
        }

        return view('auth.change_password');
    }








    public function changePassword(Request $request)
    {

        $token = session('auth_token');


        if (!$token) {
            return redirect()->route('login');
        }

        try {

            if (!$user = JWTAuth::setToken($token)->authenticate()) {
                return  'Unauthorized access';
            }
        } catch (JWTException $e) {
            return 'Token not found';
        }


        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:5|confirmed',
        ]);




        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided current password is incorrect.']);
        }



        $user->password = Hash::make($request->new_password);
        $user->password_changed = True;
        $user->save();

        Auth::login($user);

        return redirect()->route('welcome');
    }





    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
