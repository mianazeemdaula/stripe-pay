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
            if($event->id) {
                DB::beginTransaction();
                $invoice = Invoice::where('invoice_id', $event->id)->first();
                if($invoice && $event->type = 'payment_intent.succeeded') {
                    $invoice->status = 'paid';
                    $invoice->response = $event->all();
                    $invoice->data = $event->data['object']['metadata'];
                    $invoice->amount_paid = $event->data['object']['amount_received'];
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
}
