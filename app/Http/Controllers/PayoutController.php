<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payout;
use App\Models\User;

class PayoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $tag)
    {
        $user = User::where('tag', $tag)->firstOrFail();
        return view('web.payout.create', compact('tag'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $tag, Request $request)
    {
        $user = User::where('tag', $tag)->firstOrFail();
        $request->validate([
            'cashtag' => 'required|string',
            'amount' => 'required|numeric',
            'note' => 'nullable|string',
        ]);
        $payout = new Payout();
        $payout->user_id = $user->id;
        $payout->method = 'cashapp';
        $payout->account = $request->cashtag;
        $payout->amount = $request->amount;
        $payout->note = $request->note;
        $payout->save();
        return redirect()->back()->with('success', 'Request created successfully.');
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
