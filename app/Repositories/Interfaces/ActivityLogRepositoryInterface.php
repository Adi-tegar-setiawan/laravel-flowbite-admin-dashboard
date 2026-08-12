<?php

namespace App\Repositories\Interfaces;

interface ActivityLogRepositoryInterface
{
    public function all();

    public function latest(int $limit = 10);

    public function create(array $data);

    public function find(int $id);

    public function search(?string $search = null, ?string $action = null, int $perPage = 15);
}