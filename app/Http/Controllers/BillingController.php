<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Invoice;
use App\Models\User;

class BillingController extends Controller
{
    public function cashAppSession(Request $request)
    {
      $request->validate([
        'tag' => 'required',
        'amount' => 'required',
      ]);
      Stripe::setApiKey(env('STRIPE_SECRET'));
      $user = User::where('tag', $request->tag)->firstOrFail();
      $session = Session::create([
        'payment_method_types' => ['cashapp', 'card'],
        'success_url' => url("/invoice-success/$request->tag"),
        'cancel_url' => url("/invoice-cancel/$request->tag"),
        'mode' => 'payment',
        'customer_email' => $user->email,
        'payment_intent_data' => [
          'metadata' => [
            'user_tag' => $request->tag,
          ],
        ],
        'line_items' => [[
            'price_data' => [
              'currency' => 'usd',
              'product_data' => [
                'name' => 'Topup account',
              ],
              'unit_amount' => intval($request->amount * 100),
            ],
            'quantity' => 1,
          ]],
      ]);
      return response()->json($session->id, 200);
    }
}
