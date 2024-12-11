<?php

namespace App\Services;

use Square\SquareClient;
use Square\Exceptions\ApiException;

class SquareService
{
    private $client;

    public function __construct()
    {
        $this->client = new SquareClient([
            'accessToken' => env('SQUARE_ACCESS_TOKEN'),
            'environment' => env('SQUARE_ENVIRONMENT'),
        ]);
    }

    public function createInvoice($customerId, $items, $dueDate)
    {
        $invoicesApi = $this->client->getInvoicesApi();

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
            'location_id' => env('SQUARE_LOCATION_ID'),
        ];

        try {
            $response = $paymentsApi->createPayment($payment);
            return $response->getResult()->getPayment();
        } catch (ApiException $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function processCashAppPayment($source, $amount, $imkey)
    {
        $paymentsApi = $this->client->getPaymentsApi();

        $payment = [
            'sourceId' => $source,
            'amount_money' => [
                'amount' => $amount, 
                'currency' => 'USD',
            ],
            'locationId' => env('SQUARE_LOCATION_ID'),
            'idempotencyKey' => $imkey
        ];

        try {
            $response = $paymentsApi->createPayment($payment);
            return $response->getResult()->getPayment();
        } catch (ApiException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
