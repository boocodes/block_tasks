<?php

namespace Final6\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessedJobs extends Model
{
    use HasFactory;

    protected $table = 'processed_jobs';
    protected $guarded = false;

    
}
