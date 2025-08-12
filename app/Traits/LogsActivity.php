<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * Trait for logging model activities
 */
trait LogsActivity
{
    /**
     * Boot the trait
     */
    public static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            $model->logActivity('updated');
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    /**
     * Log an activity
     */
    public function logActivity($action)
    {
        if (!app()->runningInConsole()) {
            $this->activityLogs()->create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => get_class($this),
                'model_id' => $this->getKey(),
                'properties' => $this->getActivityProperties($action),
                'changes' => $this->getActivityChanges($action)
            ]);
        }
    }

    /**
     * Get the properties for the activity log
     */
    protected function getActivityProperties($action)
    {
        return [
            'attributes' => $this->getAttributes(),
            'old' => $action === 'updated' ? $this->getOriginal() : null
        ];
    }

    /**
     * Get the changes for the activity log
     */
    protected function getActivityChanges($action)
    {
        if ($action !== 'updated') {
            return null;
        }

        $changes = [];
        $original = $this->getOriginal();
        
        foreach ($this->getDirty() as $key => $value) {
            $changes[$key] = [
                'from' => $original[$key] ?? null,
                'to' => $value
            ];
        }

        return $changes;
    }

    /**
     * Get the activity logs for the model
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'subject');
    }

    /**
     * Alias for activityLogs() to maintain compatibility
     */
    public function activities()
    {
        return $this->activityLogs();
    }
}
