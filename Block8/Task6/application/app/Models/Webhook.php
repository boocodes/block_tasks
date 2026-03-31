<?php

namespace Final6\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Final6\App\Models\Project;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Webhook extends Model
{
    use HasFactory;
    protected $table = 'webhooks';
    protected $guarded = false;

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    public function attempts(): HasMany
    {
        return $this->hasMany(WebhookAttempts::class);
    }
}
