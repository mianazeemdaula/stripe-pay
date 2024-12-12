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
use Stripe\Event;

// Square
use Square\Utils\WebhooksHelper;


class PaymentHooksController extends Controller
{
    
    public function stripePayment(Request $request) {
        try {
            // Log::debug($request->headers->all());
            // Log::debug($request->all());
            $payload = file_get_contents('php://input');
            Stripe::setApiKey(env('STRIPE_SECRET'));
            $event = Event::constructFrom(
                json_decode($payload, true)
            );
            if($event->id && $event->type == 'payment_intent.succeeded' && $event->livemode == true) {
                if(isset($event->data['object']['metadata']['user_tag'])){
                    DB::beginTransaction();
                    $user = User::where('tag', $event->data['object']['metadata']['user_tag'])->first();
                    if($user) {
                        $amount = $event->data['object']['amount_received'] ?? $event->data['object']['amount_total'] ?? 0;
                        $tax = (30 + ($amount * 0.029)) / 100;
                        $tax = number_format((float)$tax, 2, '.', '');
                        $invoice = new Invoice;
                        $invoice->status = 'paid';
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

    function squareCashAppPayment(Request $request) {
        Log::debug($request->headers->all());

        // Get this funtion url 
        $notificationUrl = $request->url();
        $signatureKey = env('SQUARE_SIGNATURE_KEY');
        $signature = $request->header('x-square-hmacsha256-signature');
        $body = $request->getContent();

        $isValid = WebhooksHelper::isValidWebhookEventSignature(
            $body,
            $signature,
            $signatureKey,
            $notificationUrl
        );

        // Verify the webhook signature
        if (!$isValid) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }
        
        // Handle the event
        $event = json_decode($body, true);
        Log::info($event);
        if($event['type'] == 'payment.updated') {
            $data = $event['object']['payment'];
            if($data['status'] == 'COMPLETED'){
                $user = User::where('tag', $data['receipt_number'])->first();
                if($user) {
                    $amount = $data['approved_money']['amount'] / 100;
                    $tax = (0.10 + ($amount * 0.26));
                    $tax = number_format((float)$tax, 2, '.', '');
                    $invoice = new Invoice;
                    $invoice->status = 'paid';
                    $invoice->amount = $amount ;
                    $invoice->tax = $tax;
                    $invoice->payment_gateway_id = 2;
                    $invoice->tx_id = $data['id'];
                    $invoice->user_id = $user->id;
                    $invoice->save();
                    $user->updateBalance($invoice->amount - $tax, "Payment received by cashApp");
                }else{
                    Log::info('User not found');
                    return response()->json(['message' => 'User not found'], 404);
                }
            }
        }
        return response()->json(['message' => 'In testing phase'], 403);
    }
}
