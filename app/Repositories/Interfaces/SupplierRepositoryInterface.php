<?php

namespace App\Repositories\Interfaces;

interface SupplierRepositoryInterface
{
    public function all();

    public function find(int $id);

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);

    public function search(?string $keyword = null, int $perPage = 10);
}