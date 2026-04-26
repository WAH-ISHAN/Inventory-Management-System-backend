<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $fillable = [
    'item_id', 'borrower_name', 'contact_details', 'borrow_date',
    'expected_return_date', 'quantity_borrowed', 'returned_date', 'status',
    'condition', 'notes'
];

public function item() {
    return $this->belongsTo(Item::class);
}
}
