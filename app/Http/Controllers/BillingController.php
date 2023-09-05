<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Checkout\Session;

class BillingController extends Controller
{
    public function createCheckoutSession()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));

        $checkout_session = Session::create([
            // 'payment_method_types' => ['card','cashapp'],
            'success_url' => url('success'),
            'mode' => 'payment',
            'cancel_url' => url('cancel'),
            'line_items' => [[
                'price_data' => [
                  'currency' => 'usd',
                  'product_data' => [
                    'name' => 'T-shirt',
                  ],
                  'unit_amount' => 2000,
                ],
                'quantity' => 1,
              ]],
        ]);

        return response()->json($checkout_session, 200);
    }
}
