<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Invoice;

class BillingController extends Controller
{
    public function cashAppSession(Request $request)
    {
      $request->validate([
        'invoice_id' => 'required',
        'amount' => 'required',
      ]);
      Stripe::setApiKey(env('STRIPE_SECRET'));
      $invoice = Invoice::where('invoice_id', $request->invoice_id)->firstOrFail();
      $session = Session::create([
        'payment_method_types' => ['cashapp'],
        'success_url' => url("/invoice-success/$invoice->id"),
        'cancel_url' => url("/invoice-cancel/$invoice->id"),
        'mode' => 'payment',
        'customer_email' => Str::random(6) . '@gmail.com',
        'payment_intent_data' => [
          'metadata' => [
            'invoice_id' => $request->invoice_id,
          ],
        ],
        'line_items' => [[
            'price_data' => [
              'currency' => 'usd',
              'product_data' => [
                'name' => $invoice->product->name,
              ],
              'unit_amount' => intval($request->amount * 100),
            ],
            'quantity' => 1,
          ]],
      ]);
      Log::debug($session);
      $invoice->payment_id = $session->id;
      $invoice->amount = $request->amount;
      $invoice->save();
      return response()->json($session->id, 200);
    }
}
