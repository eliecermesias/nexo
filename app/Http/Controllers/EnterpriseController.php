<?php

namespace App\Http\Controllers;

use App\Models\Enterprises;
use Illuminate\Http\Request;

class EnterpriseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $enterprises = Enterprises::all();
        return view('enterprises.index', compact('enterprises'));
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
    public function show(Enterprises $enterprises)
    {
        return $enterprises;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Enterprises $enterprise)
    {
        return $request;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enterprises $enterprises)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enterprises $enterprises)
    {
        //
    }
}
