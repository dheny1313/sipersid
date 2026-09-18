<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        $flights = Flight::orderBy('departure_time', 'desc')->get();
        return view('admin.flights.index', compact('flights'));
    }

    public function create()
    {
        // Pastikan view resources/views/admin/flights/create.blade.php dibuat nanti
        return view('admin.flights.create');
    }

    public function store(Request $request)
    {
        Flight::create($request->all());
        return redirect()->route('admin.flights.index')->with('success', 'Data penerbangan ditambahkan.');
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
