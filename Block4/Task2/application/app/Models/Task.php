<?php

namespace Task2\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Task2\App\Enums\TaskStatus;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'tasks';
    protected $guarded = false;
    protected $casts = [
        'status' => TaskStatus::class,
    ];

}
