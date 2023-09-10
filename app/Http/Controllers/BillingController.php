<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

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
                    'name' => 'T-shirt Orion Star',
                  ],
                  'unit_amount' => intval(1 * 100),
                ],
                'quantity' => 1,
              ]],
        ]);

        return response()->json($checkout_session, 200);
    }


    function createIntent()  {

      Stripe::setApiKey(env('STRIPE_SECRET'));

        $paymentIntent = PaymentIntent::create([
            'amount' => intval(1 * 100), // amount in cents
            'currency' => 'usd',
            'payment_method_types' => ['card','cashapp'],
            'confirm' => true,
            'cancel_url' => url('cancel'),
            'return_url' => url('success'),
        ]);

        return response()->json([
            'paymentIntent' => $paymentIntent,
            'publishableKey' => env('STRIPE_KEY'),
        ]);
      
    }
}
