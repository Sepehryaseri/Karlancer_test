<?php

namespace App\Models;

use App\Casts\HashIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'task_title_id',
        'status',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    public function taskTitle(): BelongsTo
    {
        return $this->belongsTo(TaskTitle::class);
    }
}
