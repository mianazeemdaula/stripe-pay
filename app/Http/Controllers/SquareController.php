<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\SquareService;


class SquareController extends Controller
{
    private $square;

    public function __construct(SquareService $squareService)
    {
        $this->square = $squareService;

    }


    public function index()
    {
        $tag = 'f33l47GBzY';
        return view('web.checkouts.squarecashapp', compact('tag'));
        // $api_response = $client->getLocationsApi()->listLocations();

        // if ($api_response->isSuccess()) {
        //     $locations = $api_response->getResult();
        //     return view('square', compact('locations'));
        // } else {
        //     $errors = $api_response->getErrors();
        //     return response()->json($errors, 200);
        //     return view('square', compact('errors'));
        // }
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
            'nonce' => 'required',
            'amount' => 'required|integer',
        ]);

        try {
            $payment = $this->square->processCashAppPayment(
                $request->nonce,
                $request->amount
            );
            return response()->json(['payment' => $payment]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
