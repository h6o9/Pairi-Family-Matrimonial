<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarriageBureauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bureaus = \App\Models\MarriageBureau::latest()->paginate(15);
        return view('admin.marriage_bureaus.index', compact('bureaus'));
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
        $bureau = \App\Models\MarriageBureau::findOrFail($id);
        return view('admin.marriage_bureaus.show', compact('bureau'));
    }

    public function verifySubscription(string $id)
    {
        $subscription = \App\Models\MarriageBureauSubscription::findOrFail($id);
        $subscription->status = 'verified';
        $subscription->save();

        $notification = ['message' => 'Subscription verified successfully.', 'alert-type' => 'success'];
        return redirect()->back()->with($notification);
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
