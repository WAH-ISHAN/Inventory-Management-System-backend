<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Item;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BorrowingController extends Controller
{
   public function borrowItem(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'borrower_name' => 'required|string',
            'contact_details' => 'required',
            'borrow_date' => 'required|date',
            'expected_return_date' => 'required|date',
            'quantity_borrowed' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $item = Item::lockForUpdate()->findOrFail($request->item_id);
            if ($item->quantity < $request->quantity_borrowed) {
                return response()->json(['message' => 'Not enough stock!'], 400);
            }
            $item->quantity -= $request->quantity_borrowed;
            $item->status = 'Borrowed';
            $item->save();
            $borrowing = Borrowing::create($request->all());

            DB::table('audit_logs')->insert([
                'user_id' => Auth::id(),
                'action' => 'Item Borrowed',
                'model' => 'Borrowing',
                'new_values' => json_encode($borrowing->toArray()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json(['message' => 'Item borrowed successfully', 'data' => $borrowing]);
        });
    }
    public function returnItem(Request $request, $id)
    {
        return DB::transaction(function () use ($id) {
            $borrowing = Borrowing::lockForUpdate()->findOrFail($id);

            if ($borrowing->status === 'Returned') {
                return response()->json(['message' => 'Already returned!'], 400);
            }
            $item = Item::lockForUpdate()->findOrFail($borrowing->item_id);
            $item->quantity += $borrowing->quantity_borrowed;
            $item->status = 'In-Store';
            $item->save();

            $borrowing->update([
                'status' => 'Returned',
                'returned_date' => now()
            ]);

            return response()->json(['message' => 'Item returned successfully']);
        });
    }
}
