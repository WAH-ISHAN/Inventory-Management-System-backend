<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CupboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(\App\Models\Cupboard::with('places')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $cupboard = \App\Models\Cupboard::create($request->all());
        return response()->json($cupboard, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cupboard = \App\Models\Cupboard::with('places')->findOrFail($id);
        return response()->json($cupboard);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
        ]);
        $cupboard = \App\Models\Cupboard::findOrFail($id);
        $cupboard->update($request->all());
        return response()->json($cupboard);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       $cupboard = \App\Models\Cupboard::findOrFail($id);
       $cupboard->delete();
        return response()->json(['message' => 'Cupboard deleted successfully']);
    }
}
