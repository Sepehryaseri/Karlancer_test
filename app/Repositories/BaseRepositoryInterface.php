<?php

namespace App\Repositories;

use Closure;

interface BaseRepositoryInterface
{
    public function create(array $data);

    public function get(Closure $filter, array $columns = ['*']);

    public function first(int $id, array $with = []);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function findBY(array $conditions, array $with = []);
}
