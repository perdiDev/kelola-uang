<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        string $action,
        ?Model $target = null,
        string $status = 'success',
        array $metadata = []
    ): ActivityLog {
        $actor = Auth::user();
        $request = request();

        return ActivityLog::create([
            'actor_id' => $actor?->id,
            'target_type' => $target ? $target->getMorphClass() : null,
            'target_id' => $target?->getKey(),
            'action' => $action,
            'status' => $status,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
            'metadata' => $metadata,
        ]);
    }
}
