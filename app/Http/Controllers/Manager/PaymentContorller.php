<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;

class PaymentContorller extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collection = Invoice::with(['user'])->orderBy('id','desc')->paginate();
        $payableBalance = User::role('merchant')
            ->whereHas('transaction')
            ->get()
            ->sum(function($user) {
                return $user->transaction->balance;
            });
        
        return view('manager.payments.index', compact('collection', 'payableBalance'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
        $request->validate([
            'status' => 'required|in:paid,unpaid'
        ]);
        $invoice = Invoice::find($id);
        if($invoice && $invoice->status !== 'paid'){
            $invoice->status = $request->status;
            $invoice->save();
            $tax = $invoice->tax;
            $invoice->user->updateBalance($invoice->amount - $tax, "Payment received by ".$invoice->gateway->name);
            return redirect()->back()->with('success', 'Invoice status has been updated successfully.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
