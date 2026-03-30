<?php

namespace Final4\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditLogs extends Model
{
    use HasFactory, SoftDeletes;


    protected $table = 'audit_logs';
    protected $guarded = false;
    const UPDATED_AT = null;
}
