<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\User;

class InvoiceController extends Controller
{
    public function cashapp($id)
    {
        $user = User::where('tag', $id)->firstOrFail();
        $tag = $user->tag;
        return view('web.checkouts.cashapp', compact('tag'));
    }


    public function successInvoice($id)  {
        $data = User::where('tag', $id)->firstOrFail();
        return view('web.checkouts.success', compact('data'));
    }

    public function cancelInvoice($id)  {
        $data = User::where('tag', $id)->firstOrFail();
        return view('web.checkouts.cancel', compact('data'));
    }
}
