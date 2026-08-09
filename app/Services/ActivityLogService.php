<?php

namespace App\Services;

use App\Repositories\Interfaces\ActivityLogRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    public function __construct(
        protected ActivityLogRepositoryInterface $repository
    ) {
    }

    public function log(
        string $action,
        string $description,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?array $properties = null,
        ?int $userId = null
    ) {
        return $this->repository->create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'description' => $description,
            'properties' => $properties,
        ]);
    }

    public function latest(int $limit = 10)
    {
        return $this->repository->latest($limit);
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find(int $id)
    {
        return $this->repository->find($id);
    }
}