<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as BaseActivity;

class CustomActivityLog extends BaseActivity
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_log';

    /**
     * Get the subject of the activity.
     */
    public function subject()
    {
        if (config('activitylog.subject_returns_soft_deleted_models', true)) {
            return $this->morphTo('subject', 'subject_type', 'subject_id')->withTrashed();
        }

        return $this->morphTo('subject', 'subject_type', 'subject_id');
    }

    /**
     * Get the causer of the activity.
     */
    public function causer()
    {
        return $this->morphTo('causer', 'causer_type', 'causer_id');
    }

    /**
     * Get the parent of the activity.
     */
    public function parent()
    {
        if (null === $this->parent_type || null === $this->parent_id) {
            return null;
        }

        return $this->morphTo('parent', 'parent_type', 'parent_id');
    }
}
