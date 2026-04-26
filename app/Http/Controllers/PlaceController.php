<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(\App\Models\Place::with('cupboard')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cupboard_id' => 'required|exists:cupboards,id',
            'name' => 'required|string',
        ]);
        $place = \App\Models\Place::create($request->all());
        return response()->json(['message' => 'Place created', 'place' => $place], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $place = \App\Models\Place::findOrFail($id);
        return response()->json(['message' => 'Place found', 'place' => $place]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['name' => 'required|string']);
        $place = \App\Models\Place::findOrFail($id);
        $place->update($request->all());
        return response()->json(['message' => 'Place updated', 'place' => $place]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $place = \App\Models\Place::findOrFail($id);
        $place->delete();
        return response()->json(['message' => 'Place deleted successfully']);
    }
}
