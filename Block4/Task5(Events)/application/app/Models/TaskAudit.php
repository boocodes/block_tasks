<?php

namespace Task5\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;


class TaskAudit extends Model
{
    use SoftDeletes;

    protected $table = 'task_audits';
    protected $guarded = false;
    protected $casts = [
        'meta' => 'array',
        'occurred_at' => 'datetime',
    ];


    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
