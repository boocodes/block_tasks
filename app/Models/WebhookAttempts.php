<?php

namespace Final7\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Final7\App\Models\Webhook;

class WebhookAttempts extends Model
{
    use HasFactory;
    protected $table = 'webhook_attempts';
    protected $guarded = false;

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(Webhook::class);
    }
}
