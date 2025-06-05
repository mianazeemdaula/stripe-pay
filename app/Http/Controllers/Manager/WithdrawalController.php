<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use Illuminate\Support\Str;
use App\Models\User;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collection = Withdrawal::orderBy('id', 'desc')->paginate();
        return view('manager.withdrawals.index', compact('collection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role('merchant')
        ->whereHas('transaction')->get();
        return view('manager.withdrawals.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'user_id' => 'required|exists:users,id',
            'fee' => 'required|numeric|min:0',
        ]);
        $user = User::findOrFail($request->user_id);
        if ($user->transaction->balance < $request->amount) {
            return redirect()->back()->withErrors(['amount' => 'You do not have enough balance.']);
        }
        $fee = ($request->amount * $request->fee) / 100;
        $user->withdrawals()->create([
            'amount' => $request->amount - $fee,
            'fee' => $fee,
            'transfer_by' => auth()->user()->id,
            'transfer_id' => Str::random(10),
            'status' => 'approved',
        ]);
        $user->updateBalance(-$request->amount, 'Withdrawal successfully.');
        return redirect()->route('manager.withdrawals.index')->with('success', 'Withdrawal successfully.');
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
            'status' => 'required|in:pending,approved,cancelled',
        ]);
        $withdrawal = Withdrawal::findOrFail($id);
        $withdrawal->update([
            'status' => $request->status,
            'transfer_by' => auth()->user()->id,
            'transfer_id' => Str::random(10),
        ]);
        if($request->status === 'cancelled'){
            $amount = $withdrawal->amount + $withdrawal->fee;
            $withdrawal->user->updateBalance($amount, 'Withdrawal canceled.');
        }
        return redirect()->back()->with('success', 'Withdrawal status updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
