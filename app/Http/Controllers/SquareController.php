<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\SquareService;
use App\Models\User;

class SquareController extends Controller
{
    private $square;

    public function __construct(SquareService $squareService)
    {
        $this->square = $squareService;

    }

    public function index($tag)
    {
        $user = User::where('tag', $tag)->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid tag'], 404);
        }
        $tag = $user->tag;
        return view('web.checkouts.cashapp', compact('tag'));
    }

    public function getCahappPayment(Request $request, $tag){
        $request->validate([
            'amount' => 'required',
        ]);
        $user = User::where('tag', $tag)->first();
        if (!$user) {
            return response()->json(['error' => 'Invalid tag'], 404);
        }
        $tag = $user->tag;
        $amount = $request->amount;
        // check if amount is int convert it to float
        $amount = number_format((float)$amount, 2, '.', '');
        
        return view('web.checkouts.squarecashapp', compact('tag', 'amount'));
    }

    public function createInvoice(Reqeust $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'amount' => 'required|float',
        ]);
        $invoicesApi = $this->square->getInvoicesApi();
        $amount = $request->amount * 100;
        // today 
        $dueDate =  date('Y-m-d', strtotime(' + 1 day'));
        $items = [
            [
                'name' => 'Service',
                'quantity' => '1',
                'base_price_money' => [
                    'amount' => $amount,
                    'currency' => 'USD',
                ],
            ],
        ];
        $invoice = [
            'location_id' => env('SQUARE_LOCATION_ID'),
            'customer_id' => $customerId,
            'payment_requests' => [[
                'request_type' => 'BALANCE',
                'due_date' => $dueDate,
            ]],
            'line_items' => $items,
        ];

        try {
            $response = $invoicesApi->createInvoice($invoice);
            return $response->getResult()->getInvoice();
        } catch (ApiException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function processPayment($nonce, $amount)
    {
        $paymentsApi = $this->client->getPaymentsApi();

        $payment = [
            'source_id' => $nonce,
            'amount_money' => [
                'amount' => $amount,
                'currency' => 'USD',
            ],
            'location_id' => config('services.square.location_id'),
        ];

        try {
            $response = $paymentsApi->createPayment($payment);
            return $response->getResult()->getPayment();
        } catch (ApiException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function processCashAppPayment(Request $request)
    {
        $request->validate([
            'idempotencyKey' => 'required',
            'amount' => 'required',
            'sourceId' => 'required',
        ]);

        try {
            $payment = $this->square->processCashAppPayment(
                $request->sourceId,
                $request->amount,
                $request->idempotencyKey,
            );
            return response()->json(['payment' => $payment]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
