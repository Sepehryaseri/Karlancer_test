<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\BaseRepository;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    public function __construct(protected Task $task)
    {
        parent::__construct($task);
    }

    public function getTask(int $taskId, int $userId): Model|Builder|null
    {
        return $this->task->query()
            ->whereHas('taskTitle', function (Builder $builder) use ($userId) {
                $builder->where('user_id', $userId);
            })
            ->where('tasks.id', $taskId)
            ->first();
    }
}
