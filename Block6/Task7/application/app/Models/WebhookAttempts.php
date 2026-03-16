<?php

namespace Task7\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebhookAttempts extends Model
{
    use HasFactory;
    protected $table = "webhook_attempts";
    protected $guarded = false;
}
