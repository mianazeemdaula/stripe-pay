<?php

namespace App\Services;

use Square\SquareClient;
use Square\Models\CreatePaymentRequest;
use Square\Models\Money;
use Square\Exceptions\ApiException;
use Illuminate\Support\Facades\Log;

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

    public function payments()
    {
        try {
            $response = $this->client->getPaymentsApi()->listPayments();
            if ($response->isSuccess()) {
                $payments = $response->getResult()->getPayments();
                return $payments;
            } else {
                return ['status' => 'error', 'message' => $response->getErrors()];
            }
        } catch (ApiException $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
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

    public function processCashAppPayment($source, $amount, $imkey, $referenceId)
    {
        $payment  = new CreatePaymentRequest($source,$imke);
        $amount = (float)$amount;
        $amount = (int)($amount * 100);
        Log::info('Amount on processCashAppPayment: '.$amount);
        $amount_money = new \Square\Models\Money();
        $amount_money->setAmount($amount);
        $amount_money->setCurrency('USD');
        
        $payment->setAutocomplete(true);
        $payment->setAmountMoney($amount_money);
        $payment->setLocationId(env('SQUARE_LOCATION_ID'));
        $payment->setReferenceId($referenceId);
        try {
            $response = $this->client->getPaymentsApi()->createPayment($payment);
            if ($response->isSuccess()) {
                $result = $response->getResult();
                return ['status' => 'success', 'payment' => $result->getPayment()];
            } else {
                $errors = $response->getErrors();
                return ['status' => 'error', 'message' => $errors];
            }
        } catch (ApiException $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
