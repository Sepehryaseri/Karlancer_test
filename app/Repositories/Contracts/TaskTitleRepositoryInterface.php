<?php

namespace App\Repositories\Contracts;

use App\Models\TaskTitle;
use App\Repositories\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

interface TaskTitleRepositoryInterface extends BaseRepositoryInterface
{
    public function syncCategories(TaskTitle|Builder $taskTitle, array $categoryIds);
}
