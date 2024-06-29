<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Traits\Exceptionable;
use App\Traits\HashIdConverter;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class TaskService
{
    use Exceptionable, HashIdConverter;

    protected User $user;

    public function __construct(protected TaskRepositoryInterface $taskRepository,
                                protected TaskTitleService $taskTitleService)
    {
        $this->user = auth('sanctum')->user();
    }

    public function create(string $taskTitleHashId, array $data): array
    {
        try {
            $taskTitleResult = $this->taskTitleService->find($taskTitleHashId);
            if ($taskTitleResult['status'] != Response::HTTP_OK) {
                throw new Exception(message: $taskTitleResult['message'], code: $taskTitleResult['status']);
            }
            $data['task_title_id'] = $taskTitleResult['id'];
            $task = $this->taskRepository->create($data);
            return [
                'status' => Response::HTTP_CREATED,
                'message' => __('task.created')
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function getList(string $taskTitleHashId): array
    {
        try {
            $taskTitleResult = $this->taskTitleService->find($taskTitleHashId);
            if ($taskTitleResult['status'] != Response::HTTP_OK) {
                throw new Exception(message: $taskTitleResult['message'], code: $taskTitleResult['status']);
            }
            $tasks = $this->taskRepository->get(function (Builder $builder) use ($taskTitleResult) {
                return $builder->where('task_title_id', $taskTitleResult['id']);
            });
            $tasks->each(function ($item) {
                $item->id = $this->hash($item->id, 'task');
            });
            return [
                'status' => Response::HTTP_OK,
                'data' => $tasks->toArray(),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function find(string $taskHashId): array
    {
        try {
            $taskId = $this->deHash($taskHashId, 'task');
            $task = $this->taskRepository->getTask($taskId, $this->user->id);
            if (!isset($task)) {
                throw new NotFoundResourceException(message: __('task.not_found'), code: Response::HTTP_NOT_FOUND);
            }
            return [
                'status' => Response::HTTP_OK,
                'id' => $taskId,
                'data' => $task->toArray(),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function update(string $taskHashId, array $data): array
    {
        try {
            $taskResult = $this->find($taskHashId);
            if ($taskResult['status'] != Response::HTTP_OK) {
                throw new Exception(message: $taskResult['message'], code: $taskResult['status']);
            }
            $this->taskRepository->update($taskResult['id'], $data);
            return [
                'status' => Response::HTTP_OK,
                'message' => __('task.updated'),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function delete(string $taskHashId): array
    {
        try {
            $taskResult = $this->find($taskHashId);
            if ($taskResult['status'] != Response::HTTP_OK) {
                throw new Exception(message: $taskResult['message'], code: $taskResult['status']);
            }
            $this->taskRepository->delete($taskResult['id']);
            return [
                'status' => Response::HTTP_OK,
                'message' => __('task.deleted')
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }
}
