<?php

namespace App\Repositories\Eloquent;

use App\Models\TaskTitle;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\TaskTitleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

class TaskTitleRepository extends BaseRepository implements TaskTitleRepositoryInterface
{
    public function syncCategories(TaskTitle|Builder $taskTitle, array $categoryIds)
    {
        $taskTitle->categories()->sync($categoryIds);
    }
}
