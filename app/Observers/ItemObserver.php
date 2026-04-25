<?php

namespace App\Observers;

use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class ItemObserver
{
    /**
     * Handle the Item "created" event.
     */
    public function created(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "updated" event.
     */
    public function updated(Item $item): void
    {
        if ($item->isDirty()) {
        \App\Models\AuditLog::create([
            'user_id' => Auth::id() ?? 1,
            'action' => 'Item Updated',
            'model' => 'Item',
            'old_values' => $item->getOriginal(),
            'new_values' => $item->getAttributes(),
        ]);
        }
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "restored" event.
     */
    public function restored(Item $item): void
    {
        //
    }

    /**
     * Handle the Item "force deleted" event.
     */
    public function forceDeleted(Item $item): void
    {
        //
    }
}
