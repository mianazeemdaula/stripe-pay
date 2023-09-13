<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;


class AuthController extends Controller
{

    public function login()
    {
        if (Auth::check()) {
            return  redirect('dashboard');
        }
        return view('auth.login');
    }

    public function doLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        $user =  User::where('email', $request->email)->first();
       if ($user && Hash::check($request->password, $user->password)) {
           Auth::login($user);
           return redirect()->intended('dashboard');
        }else{
            return redirect()->back()->withErrors(['password' => 'Password not matched.']);
        }
    }

    public function dashboard()
    {
        $balance = null;
        if(auth()->user()->type == 'admin'){
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $balance = \Stripe\Balance::retrieve();
        }
        return view('auth.dashboard', compact('balance'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
