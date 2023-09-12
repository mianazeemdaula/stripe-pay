<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Log;
use App\Models\Invoice;

// Stripe
use Stripe\Stripe;
use Stripe\PaymentIntent;

class PaymentHooksController extends Controller
{
    

    
    function stripePayment(Request $event) {
        try {
            if($event->id && $event->type = 'payment_intent.succeeded') {
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
        } catch (\Exeception $th) {
            DB::rollBack();
            Log::debug($th);
        }
    }

    function stripeLinkPayment(Request $event) {
        try {
            if($event->id && $event->type = 'checkout.session.completed') {
                
                Log::debug($event->all());
                // DB::beginTransaction();
                // $invoice = Invoice::where('invoice_id', $event->data['object']['metadata']['invoice_id'])->first();
                // if($invoice) {
                //     $invoice->status = 'paid';
                //     $invoice->response = $event->all();
                //     $invoice->data = $event->data['object']['metadata'];
                //     $invoice->amount_paid = intval($event->data['object']['amount_received'] /  100);
                //     $invoice->save();
                //     $invoice->user->updateBalance($invoice->amount_paid, 'Payment received for invoice #'.$invoice->invoice_id);
                // }
                // DB::commit();
            }
        } catch (\Exeception $th) {
            DB::rollBack();
            Log::debug($th);
        }
    }
}
