<?php

namespace App\Services;

use App\Models\TaskTitle;
use App\Models\User;
use App\Repositories\Contracts\TaskTitleRepositoryInterface;
use App\Traits\Exceptionable;
use App\Traits\HashIdConverter;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class TaskTitleService
{
    use Exceptionable, HashIdConverter;

    protected User $user;

    public function __construct(private readonly TaskTitleRepositoryInterface $taskTitleRepository)
    {
        $this->user = auth('sanctum')->user();
    }

    private function assignCategories(array $categories, TaskTitle|Builder $taskTitle): array
    {
        try {
            $categoriesIds = [];
            foreach ($categories as $categoryId) {
                $categoriesIds[] = $this->deHash($categoryId, 'category');
            }
            $this->taskTitleRepository->syncCategories($taskTitle, $categoriesIds);
            return [
                'status' => Response::HTTP_OK
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function create(array $data): array
    {
        try {
            $data['user_id'] = $this->user->id;
            $taskTitle = $this->taskTitleRepository->create($data);
            if (isset($data['categories'])) {
                $this->assignCategories($data['categories'], $taskTitle);
            }
            return [
                'status' => Response::HTTP_CREATED,
                'message' => __('taskTitle.create')
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function getList(array $data): array
    {
        try {
            $taskTitles = $this->taskTitleRepository->get(function (Builder $builder) use ($data) {
                return $builder
                    ->when(!empty($data['name']), function (Builder $query) use ($data) {
                        $query->where('name', 'LIKE', '%' . $data['name'] . '%');
                    })
                    ->when(!empty($data['from_date']), function (Builder $query) use ($data) {
                        $query->whereDate('due_date', '>=', date('Y-m-d 00:00:00', strtotime($data['from_date'])));
                    })
                    ->when(!empty($data['to_date']), function (Builder $query) use ($data) {
                        $query->whereDate('due_date', '<=', date('Y-m-d 23:59:59', strtotime($data['to_date'])));
                    })
                    ->where('user_id', '=', $this->user->id)
                    ->with(['categories:id,name']);
            });

            $taskTitles->each(function ($item) {
                $item->id = $this->hash($item->id, 'task_title');
                $item->categories->each(function ($item) {
                   $item->id = $this->hash($item->id, 'category');
                });
            });

            return [
                'status' => Response::HTTP_OK,
                'data' => $taskTitles->toArray()
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function find(string $taskTitleId): array
    {
        try {
            $taskTitleId = $this->deHash($taskTitleId, 'task_title');
            $taskTitle = $this->taskTitleRepository->findBY([
                ['id', '=', $taskTitleId],
                ['user_id', '=', $this->user->id]
            ], [
                'categories:id,name',
                'tasks:task_title_id,id,name'
            ]);
            $taskTitle->id = $this->hash($taskTitle->id, 'task_title');
            $taskTitle->categories->each(function ($item) {
                $item->id = $this->hash($item->id, 'category');
            });
            if (!isset($taskTitle)) {
                throw new NotFoundResourceException(__('taskTitle.not_found'), 404);
            }
            return [
                'status' => Response::HTTP_OK,
                'data' => $taskTitle,
                'id' => $taskTitleId,
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function get(string $taskTitleId): array
    {
        try {
            $result = $this->find($taskTitleId);
            if ($result['status'] != Response::HTTP_OK) {
                throw new Exception($result['message'], $result['status']);
            }
            return [
                'status' => Response::HTTP_OK,
                'data' => $result['data']->toArray(),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function update(string $taskTitleId, array $data): array
    {
        try {
            $result = $this->find($taskTitleId);
            if ($result['status'] != Response::HTTP_OK) {
                throw new Exception($result['message'], $result['status']);
            }
            $this->taskTitleRepository->update($result['id'], $data);
            $taskTitle = $result['data'];
            if (isset($data['categories'])) {
                $this->assignCategories($data['categories'], $taskTitle);
            }
            return [
                'status' => Response::HTTP_OK,
                'message' => __('taskTitle.updated'),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function delete(string $taskTitleId): array
    {
        try {
            $result = $this->find($taskTitleId);
            if ($result['status'] != Response::HTTP_OK) {
                throw new Exception($result['message'], $result['status']);
            }
            $this->taskTitleRepository->delete($result['id']);
            return [
                'status' => Response::HTTP_OK,
                'message' => __('taskTitle.deleted'),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

}
