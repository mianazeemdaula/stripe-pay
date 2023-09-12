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
            if($event->id && $event->type = 'checkout.session.completed') {
                DB::beginTransaction();
                $userId = 1;
                if(isset($event->data['object']['metadata']['customer_id'])){
                    $userId = $event->data['object']['metadata']['customer_id'];
                }
                $invoice = new Invoice;
                $invoice->invoice_id = $event->id;
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
}
