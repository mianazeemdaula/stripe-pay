<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Stripe\Stripe;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


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
        $sales = \App\Models\Invoice::where('created_at', '>=', Carbon::now()->subDays(10))
        ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(amount_paid - tax) as total'));
        if(auth()->user()->type !== 'admin'){
            $sales->whereUserId(auth()->user()->id);
        }
        $sales->groupBy(DB::raw('DATE(created_at)'))
        ->get();
        return view('auth.dashboard', compact('balance', 'sales'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
