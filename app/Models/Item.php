<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'code', 'quantity', 'serial_number', 'image', 'description', 'place_id', 'status'];

    public function place(){
        return $this->belongsTo(Place::class);
    }
}
