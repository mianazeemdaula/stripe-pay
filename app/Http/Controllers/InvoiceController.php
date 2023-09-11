<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function cashapp($id)
    {
        $invoice = Invoice::where('invoice_id', $id)->firstOrFail();
        return view('web.checkouts.cashapp', compact('invoice'));
    }


    public function successInvoice($id)  {
        $data = Invoice::findOrFail($id);
        return view('web.checkouts.success', compact('data'));
    }

    public function cancelInvoice($id)  {
        $data = Invoice::findOrFail($id);
        return view('web.checkouts.cancel', compact('data'));
    }
}
