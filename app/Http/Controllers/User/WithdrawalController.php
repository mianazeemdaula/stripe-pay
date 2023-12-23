<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections = auth()->user()->withdrawals()->orderBy('id', 'desc')->paginate(10);
        return view('user.withdrawals.index', compact('collections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.withdrawals.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);
        $user = auth()->user();
        if ($user->transaction->balance < $request->amount) {
            return redirect()->back()->withErrors(['amount' => 'You do not have enough balance.']);
        }
        $fee = ($request->amount * $user->fee) / 100;
        $user->withdrawals()->create([
            'amount' => $request->amount - $fee,
            'fee' => $fee,
        ]);
        $user->updateBalance(-$request->amount, 'Withdrawal request sent.');
        return redirect()->route('user.withdrawals.index')->with('success', 'Withdrawal request sent successfully.');
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
