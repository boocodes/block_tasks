<?php

namespace Final7\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Metrics extends Model
{
    use HasFactory;

    protected $table = 'metrics';
    protected $guarded = false;

}
