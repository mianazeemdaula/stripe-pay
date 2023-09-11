<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Invoice;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collection = Invoice::where('user_id', auth()->user()->id)->get();
        return view('user.invoice.index', compact('collection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = \App\Models\Product::where('user_id', auth()->user()->id)->get();
        return view('user.invoice.create', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
        ]);
        $invoice = new Invoice();
        $invoice->invoice_id = \Illuminate\Support\Str::random(10);
        $invoice->user_id = auth()->user()->id;
        $invoice->payment_gateway_id = 1;
        $invoice->product_id = $request->product_id;
        $invoice->save();
        return redirect()->route('user.invoices.index')->with('success', 'Invoice created successfully.');
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
