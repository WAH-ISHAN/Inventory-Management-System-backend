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
        \DB::table('audit_logs')->insert([
            'user_id' => Auth::id() ?? 1,
            'action' => 'Item Created',
            'model' => 'Item',
            'new_values' => json_encode($item->getAttributes()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Handle the Item "updated" event.
     */
    public function updated(Item $item): void
    {
        if ($item->isDirty()) {
            \DB::table('audit_logs')->insert([
                'user_id' => Auth::id() ?? 1,
                'action' => 'Item Updated',
                'model' => 'Item',
                'old_values' => json_encode($item->getOriginal()),
                'new_values' => json_encode($item->getAttributes()),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Handle the Item "deleted" event.
     */
    public function deleted(Item $item): void
    {
        \DB::table('audit_logs')->insert([
            'user_id' => Auth::id() ?? 1,
            'action' => 'Item Deleted',
            'model' => 'Item',
            'old_values' => json_encode($item->getAttributes()),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
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
