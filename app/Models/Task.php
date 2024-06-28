<?php

namespace App\Models;

use App\Casts\HashIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,  SoftDeletes;

    protected $fillable = [
        'name',
        'task_title_id',
        'status',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected $hidden = [
        'deleted_at',
    ];

    public function taskTitle(): BelongsTo
    {
        return $this->belongsTo(TaskTitle::class);
    }
}
