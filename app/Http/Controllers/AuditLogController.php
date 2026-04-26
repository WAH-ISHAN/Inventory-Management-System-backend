<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;


class AuditLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = AuditLog::with('user:id,email')
            ->latest()
            ->get()
            ->map(function (AuditLog $log) {
                $newValues = is_array($log->new_values) ? $log->new_values : [];
                $oldValues = is_array($log->old_values) ? $log->old_values : [];

                $entityName = $newValues['name']
                    ?? $newValues['email']
                    ?? $oldValues['name']
                    ?? $oldValues['email']
                    ?? null;

                return [
                    'id' => $log->id,
                    'user_id' => $log->user_id,
                    'user_email' => $log->user?->email,
                    'action' => $log->action,
                    'model' => $log->model,
                    'description' => $entityName
                        ? sprintf('%s (%s: %s)', $log->action, $log->model, $entityName)
                        : sprintf('%s (%s)', $log->action, $log->model),
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                    'created_at' => $log->created_at,
                    'updated_at' => $log->updated_at,
                ];
            })
            ->values();

        return response()->json($logs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
