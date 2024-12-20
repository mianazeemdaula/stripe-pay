<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\SquareService;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Square\Models\Payment;

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
        return view('web.checkouts.square', compact('tag'));
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
        // convert amount to float with 2 decimal places
        $amount = number_format((float)$request->amount, 2, '.', '');
        // convert to string
        $amount = (string)$amount;
        Log::info('Amount on getCahappPayment: '.$amount);
        return view('web.checkouts.squarecashapp', compact('tag', 'amount'));
    }

    public function processCashAppPayment(Request $request)
    {
        $request->validate([
            'idempotencyKey' => 'required',
            'amount' => 'required',
            'sourceId' => 'required',
            'referenceId' => 'required',
        ]);

        try {
            $payment = $this->square->processCashAppPayment(
                $request->sourceId,
                $request->amount,
                $request->idempotencyKey,
                $request->referenceId,
            );
            Log::info('CashApp payment', $payment);
            if($payment['status'] == 'success'){
                // update user balance
                return response()->json($payment, 200);
            }
            return response()->json($payment, 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
