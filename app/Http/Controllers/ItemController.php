<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Item::with('place.cupboard')->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required|unique:items',
            'quantity' => 'required|integer',
            'place_id' => 'required|exists:places,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'status' => 'required|in:In-Store,Borrowed,Damaged,Missing'
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }
        $item = Item::create($data);
        return response()->json($item, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateQuantity(Request $request, $id)
    {
        $item = Item::findOrFail($id);
    {
        $request->validate([
            'action' => 'required|in:increment,decrement',
            'amount' => 'required|integer|min:1'
        ]);

        if ($request->action === 'increment') {
            $item->increment('quantity', $request->amount);
        } else {
            if ($item->quantity < $request->amount) {
                return response()->json(['message' => 'Not enough stock!'], 400);
            }
            $item->decrement('quantity', $request->amount);
        }

        return response()->json(['message' => 'Quantity updated', 'current_quantity' => $item->quantity]);

    }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Item::findOrFail($id);
        $item->delete();
        return response()->json(['message' => 'Item deleted']);
    }


    public function updateStatus(Request $request, $id)
    {

        $item = Item::findOrFail($id);

        $request->validate([
            'status' => 'required|in:In-Store,Borrowed,Damaged,Missing'
        ]);

        $item->update([
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Status updated successfully',
            'item_name' => $item->name,
            'new_status' => $item->status
        ]);
    }
}
