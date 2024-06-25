<?php

namespace App\Repositories;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{

    public function __construct(protected Model $model)
    {
    }

    public function create(array $data): Model|Builder
    {
        return $this->model
            ->query()
            ->create($data);
    }

    public function get(Closure $filter, array $columns = ['*']): Collection|LengthAwarePaginator|array
    {
        $result = $this->model->query();
        $result = $filter($result);
        if (empty($data['page'])) {
            return $result->get($columns);
        }

        return $result->paginate(perPage: $data['size'], columns: $columns, page: $data['page']);
    }

    public function first(int $id, array $with = []): Model|Builder|null
    {
        $result = $this->model->query()
            ->where('id', $id);
        if (!empty($with)) {
            $result = $result->with($with);
        }
        return $result->first();
    }

    public function update(int $id, array $data): int
    {
        return $this->model->query()
            ->where('id', $id)
            ->update($data);
    }

    public function delete(int $id)
    {
        $this->model
            ->query()
            ->where('id', $id)
            ->delete();
    }

    public function findBY(array $conditions, array $with = []): Model|Builder|null
    {
        $result = $this->model
            ->query()
            ->where($conditions);
        if (!empty($with)) {
            $result = $result->with($with);
        }
        return $result->first();
    }
}

