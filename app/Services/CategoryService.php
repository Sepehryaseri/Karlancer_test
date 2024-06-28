<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Traits\Exceptionable;
use App\Traits\HashIdConverter;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CategoryService
{
    use Exceptionable, HashIdConverter;

    protected User $user;

    public function __construct(protected CategoryRepositoryInterface $categoryRepository)
    {
        $this->user = auth('sanctum')->user();
    }

    public function create(array $data): array
    {
        try {
            $data['user_id'] = $this->user->id;
            $category = $this->categoryRepository->create($data);
            return [
                'status' => Response::HTTP_CREATED,
                'message' => __('category.created'),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function getList(array $data): array
    {
        try {
            $categories = $this->categoryRepository->get(function (Builder $builder) use ($data) {
               return $builder->when(!empty($data['name']), function (Builder $query) use ($data) {
                   $query->where('name', 'LIKE', '%'.$data['name'].'%');
               })
                   ->where('user_id', $this->user->id);
            });

            $categories->each(function ($item) {
                $item->id = $this->hash($item->id, 'category');
            });

            return [
                'status' => Response::HTTP_OK,
                'data' => $categories->toArray(),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    private function find(string $categoryHashId): array
    {
        try {
            $categoryId = $this->deHash($categoryHashId, 'category');
            $category = $this->categoryRepository->findBY([
                ['id', '=', $categoryId],
                ['user_id', '=', $this->user->id]
            ]);
            if (!isset($category)) {
                throw new NotFoundResourceException(__('taskTitle.not_found'), 404);
            }

            return [
                'status' => 200,
                'id' => $categoryId,
                'data' => $category,
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function get(string $categoryHashId): array
    {
        try {
            $categoryResult = $this->find($categoryHashId);
            if ($categoryResult['status'] != Response::HTTP_OK) {
                throw new Exception(message: $categoryResult['message'], code: $categoryResult['status']);
            }
            $category = $categoryResult['data'];
            $category->id = $this->hash($category->id, 'category');
            return [
                'status' => Response::HTTP_OK,
                'data' => $category->toArray(),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function update(string $categoryHashId, array $data): array
    {
        try {
            $result = $this->find($categoryHashId);
            if ($result['status'] != Response::HTTP_OK) {
                throw new Exception(message: $result['message'], code: $result['status']);
            }
            $this->categoryRepository->update($result['id'], $data);
            return [
                'status' => Response::HTTP_OK,
                'message' => __('category.updated'),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }

    public function delete(string $categoryHashId): array
    {
        try {
            $result = $this->find($categoryHashId);
            if ($result['status'] != Response::HTTP_OK) {
                throw new Exception($result['message'], $result['status']);
            }
            $this->categoryRepository->delete($result['id']);
            return [
                'status' => Response::HTTP_OK,
                'message' => __('taskTitle.deleted'),
            ];
        } catch (Exception $exception) {
            return $this->except($exception);
        }
    }
}
