<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Invoice;

// Stripe
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Transactions;

class PaymentHooksController extends Controller
{
    

    
    function stripePayment(Request $event) {
        try {
            if($event->id && $event->type == 'payment_intent.succeeded') {
                if(isset($event->data['object']['metadata']['invoice_id'])){
                    DB::beginTransaction();
                    $invoice = Invoice::where('invoice_id', $event->data['object']['metadata']['invoice_id'])->first();
                    if($invoice) {
                        $invoice->status = 'paid';
                        $invoice->response = $event->all();
                        $invoice->data = $event->data['object']['metadata'];
                        $invoice->amount_paid = intval($event->data['object']['amount_received'] /  100);
                        $invoice->save();
                        $invoice->user->updateBalance($invoice->amount_paid, 'Payment received for invoice #'.$invoice->invoice_id);
                    }
                    DB::commit();
                }
            }
        } catch (\Exeception $th) {
            DB::rollBack();
            Log::debug($th);
        }
    }

    function stripeLinkPayment(Request $event) {
        try {
            if($event->id && $event->type == 'checkout.session.completed' && $event->livemode == true) {
                DB::beginTransaction();
                $userId = 1;
                if(isset($event->data['object']['metadata']['customer_id'])){
                    $userId = $event->data['object']['metadata']['customer_id'];
                }
                $invoice = new Invoice;
                $invoice->payment_id = $event->id;
                $invoice->invoice_id = Str::random(10);
                $invoice->payment_gateway_id = 1;
                $invoice->user_id = $userId;
                $invoice->product_id = 1;
                $invoice->status = 'paid';
                $invoice->response = $event->all();
                $invoice->data = $event->data['object']['metadata'];
                $invoice->amount_paid = $event->data['object']['amount_total'] /  100;
                $invoice->save();
                $invoice->user->updateBalance($invoice->amount_paid, 'Payment received for invoice #'.$invoice->id);
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
}
