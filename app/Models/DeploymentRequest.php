<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeploymentRequest extends Model
{
    protected $primaryKey = 'requestID';
    public $incrementing = true;
    public $timestamps = true;

    protected $fillable = [
        'deployedID',
        'requestType',
        'requestBy',
        'requestDate',
        'checkedBy',
        'remarks',
        'status'
    ];

    protected $casts = [
        'requestDate' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the deployed item that the request is for.
     */
    public function deployedItem(): BelongsTo
    {
        return $this->belongsTo(DeployedItem::class, 'deployedID', 'deployedID');
    }

    /**
     * Get the user who made the request.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requestBy', 'id');
    }

    /**
     * Get the user who checked/approved the request.
     */
    public function checker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'checkedBy', 'id');
    }
}
