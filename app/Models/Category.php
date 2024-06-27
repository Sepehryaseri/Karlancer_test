<?php

namespace App\Models;

use App\Casts\HashIdCast;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'user_id'
    ];

    protected $casts = [
       'id' => 'string'
    ];

    protected $hidden = ['pivot'];


    public function task_titles(): BelongsToMany
    {
        return $this->belongsToMany(TaskTitle::class, 'category_title')->withTimestamps();
    }

}
