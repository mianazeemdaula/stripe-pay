<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Dispute;

class DisputeController extends Controller
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
        $reasons = [
            "It's a scam",
            "It's spamming",
            "It's inappropriate",
        ];
        $auth = User::where('tag', $tag)->firstOrFail();
        return view('web.dispute.create', compact('tag', 'reasons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $tag, Request $request)
    {
        $auth = User::where('tag', $tag)->firstOrFail();
        $request->validate([
            'email' => 'required|email',
            'reason' => 'required|string',
        ]);
        $dispute = new Dispute();
        $dispute->user_id = $auth->id;
        $dispute->email = $request->email;
        $dispute->reason = $request->reason;
        $dispute->save();
        return redirect()->back()->with( 'success','Dispute created successfully.');
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
