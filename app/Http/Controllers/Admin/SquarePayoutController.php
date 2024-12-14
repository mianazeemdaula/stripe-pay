<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Payout;

class SquarePayoutController extends Controller
{
    public function index() {
        $square =  new \App\Services\SquareService();
        $payments = $square->payments();
        $payments = json_decode(json_encode($payments), true);
        return view('admin.square.payments', compact('payments'));
    }
}
