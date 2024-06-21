<?php

namespace App\Repositories;

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

    public function get(array $columns): Collection|LengthAwarePaginator|array
    {
        $result = $this->model->query();
        if (empty($data['page'])) {
            return $result->get($columns);
        }

        return $result->paginate(perPage: $data['size'], columns: $columns, page: $data['page']);
    }

    public function first(int $id): Model|Builder|null
    {
        return $this->model->query()
            ->where('id', $id)
            ->first();
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

    public function findBY(string $column, mixed $value): Model|Builder|null
    {
        return $this->model
            ->query()
            ->where($column, $value)
            ->first();
    }
}

