<?php

namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function all();

    public function paginate(int $perPage = 10);

    public function search(?string $keyword, int $perPage = 10);

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}