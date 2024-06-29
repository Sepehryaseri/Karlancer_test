<?php

namespace App\Models;

use App\Casts\HashIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'user_id'
    ];

    protected $casts = [
       'id' => 'string'
    ];

    protected $hidden = [
        'pivot',
        'deleted_at'
        ];


    public function task_titles(): BelongsToMany
    {
        return $this->belongsToMany(TaskTitle::class, 'category_title')->withTimestamps();
    }

}
