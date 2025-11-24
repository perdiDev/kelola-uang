<?php

namespace App\Observers;

use App\Models\ActivityLog;
use App\Services\ActivityLogger;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        $this->log($model, 'created', [
            'attributes' => $this->attributes($model),
        ]);
    }

    public function updated(Model $model): void
    {
        $changes = $this->changes($model);

        if (empty($changes)) {
            return;
        }

        $this->log($model, 'updated', [
            'changes' => $changes,
        ]);
    }

    public function deleted(Model $model): void
    {
        $this->log($model, 'deleted');
    }

    public function restored(Model $model): void
    {
        $this->log($model, 'restored');
    }

    public function forceDeleted(Model $model): void
    {
        $this->log($model, 'force_deleted');
    }

    private function log(Model $model, string $event, array $metadata = []): void
    {
        // Avoid recursion and errors before migration is run
        if ($model instanceof ActivityLog || ! Schema::hasTable('activity_logs')) {
            return;
        }

        ActivityLogger::log(
            $this->actionName($model, $event),
            $model,
            'success',
            array_filter($metadata)
        );
    }

    private function actionName(Model $model, string $event): string
    {
        return strtolower(class_basename($model)).'.'.$event;
    }

    private function attributes(Model $model): array
    {
        $fillable = $model->getFillable();

        return empty($fillable)
            ? $model->getAttributes()
            : $model->only($fillable);
    }

    private function changes(Model $model): array
    {
        $dirty = $model->getDirty();

        // Only include changed fillable attributes to reduce noise
        $fillable = $model->getFillable();
        $changes = empty($fillable) ? $dirty : array_intersect_key($dirty, array_flip($fillable));

        return $changes;
    }
}
