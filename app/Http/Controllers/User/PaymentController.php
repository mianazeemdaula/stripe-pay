<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;
use App\Models\Invoice;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $gateways = PaymentGateway::all();
        return view('user.payments.create', compact('gateways'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'gateway' => 'required',
            'amount' => 'required|numeric',
        ]);

        $invoice = new Invoice();
        $invoice->user_id = auth()->id();
        $invoice->payment_gateway_id = $request->gateway;
        $invoice->amount = $request->amount;
        $invoice->tax = 0;
        $invoice->tx_id = 'TXN_'.rand(100000,999999);
        $invoice->save();
        return redirect()->route('user.invoices.index')->with('success', 'Payment has been made successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
