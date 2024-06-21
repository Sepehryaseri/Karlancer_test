<?php

namespace App\Repositories;

interface BaseRepositoryInterface
{
    public function create(array $data);

    public function get(array $columns);

    public function first(int $id);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function findBY(string $column, mixed $value);
}
