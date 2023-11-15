<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Invoice;

use App\Models\User;

// Stripe
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Transactions;


class PaymentHooksController extends Controller
{
    
    public function stripePayment(Request $event) {
        try {
            if($event->id && $event->type == 'payment_intent.succeeded') {
                if(isset($event->data['object']['metadata']['user_tag'])){
                    DB::beginTransaction();
                    $user = User::where('tag', $event->data['object']['metadata']['user_tag'])->first();
                    if($user) {
                        $amount = $event->data['object']['amount_received'] ?? $event->data['object']['amount_total'] ?? 0;
                        $tax = (30 + ($amount * 0.029)) / 100;
                        $tax = number_format((float)$tax, 2, '.', '');
                        $invoice = new Invoice;
                        $invoice->status = 'paid';
                        $invoice->data = $event->data['object']['metadata'];
                        $invoice->amount = ($amount / 100);
                        $invoice->tax = $tax;
                        $invoice->payment_gateway_id = 1;
                        $invoice->tx_id = $event->id;
                        $invoice->user_id = $user->id;
                        $invoice->save();
                        $user->updateBalance($invoice->amount - $tax, "Payment received by cashApp");
                    }
                    DB::commit();
                }
            }
        } catch (\Exeception $th) {
            DB::rollBack();
            Log::debug($th);
        }
    }

    public function stripeLinkPayment(Request $event) {
        try {
            if($event->id && $event->type == 'checkout.session.completed' && $event->livemode == true) {
                DB::beginTransaction();
                $userId = 1;
                if(isset($event->data['object']['metadata']['customer_id'])){
                    $userId = $event->data['object']['metadata']['customer_id'];
                }
                $amount = $event->data['object']['amount_total'];
                $tax = (30 + ($amount * 0.029)) /  100;
                $tax = number_format((float)$tax, 2, '.', '');
                $invoice = new Invoice;
                $invoice->tx_id = $event->id;
                $invoice->payment_gateway_id = 1;
                $invoice->user_id = $userId;
                $invoice->status = 'paid';
                $invoice->amount = ($amount / 100);
                $invoice->tax = $tax;
                $invoice->save();
                $invoice->user->updateBalance($invoice->amount - $tax, 'Payment received for invoice #'.$invoice->id);
                DB::commit();
            }
        } catch (\Exeception $th) {
            DB::rollBack();
            Log::debug($th);
        }
    }

    public function getAllPaymentsWithMetadata(Request $request)
    {
        // Set the Stripe API key
        Stripe::setApiKey(env('STRIPE_SECRET'));

        // Get all payments with specific metadata
        $payments = \Stripe\PaymentIntent::search([
            'limit' => 10,
            // 'status' => 'succeeded',
            'query' => "metadata['customer_id']:'2'",
        ]);

        // $payments = \Stripe\Transactions::all([
        //     'limit' => 10,
        //     'metadata' => ['customer_id' => '2'],
        //     'query' => "metadata['customer_id']:'2'",
        // ]);

        return response()->json($payments);
    }

    function squareCashAppPayment(Request $event) {
        Log::debug($event->all());
    }
}
