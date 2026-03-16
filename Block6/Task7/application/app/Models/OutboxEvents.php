<?php

namespace Task7\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Task7\App\Enums\OutboxEventsStatus;

class OutboxEvents extends Model
{
    use HasFactory;
    protected $guarded = false; 
    public $timestamps = false; 
    protected $casts = [
        'status' => OutboxEventsStatus::class,
        'payload' => 'array',
        'available_at' => 'datetime'
    ];
}
