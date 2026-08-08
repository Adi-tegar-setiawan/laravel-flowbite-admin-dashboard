<?php

namespace App\Repositories\Interfaces;

interface ActivityLogRepositoryInterface
{
    public function all();

    public function paginate(int $perPage = 15);

    public function create(array $data);

    public function getLatest(int $limit = 10);
}