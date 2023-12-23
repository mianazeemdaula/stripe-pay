<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use Illuminate\Support\Str;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collection = Withdrawal::orderBy('id', 'desc')->paginate();
        return view('admin.withdrawals.index', compact('collection'));
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
