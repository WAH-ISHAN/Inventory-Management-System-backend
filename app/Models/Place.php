<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Place extends Model
{
    use HasFactory;

    protected $fillable = ['cupboard_id', 'name'];

    public function cupboard():BelongsTo{
        return $this->belongsTo(Cupboard::class);
    }
}
