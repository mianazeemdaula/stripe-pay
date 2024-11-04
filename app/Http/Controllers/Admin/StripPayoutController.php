<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Payout;

class StripPayoutController extends Controller
{
    public function index() {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        $payouts = Payout::all();
        return view('admin.payouts.index', compact('payouts'));
    }
}
