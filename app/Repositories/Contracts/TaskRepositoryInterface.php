<?php

namespace App\Repositories\Contracts;

use App\Repositories\BaseRepositoryInterface;

interface TaskRepositoryInterface extends BaseRepositoryInterface
{
    public function getTask(int $taskId, int $userId);
}
