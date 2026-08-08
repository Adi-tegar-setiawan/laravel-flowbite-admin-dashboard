<?php

namespace App\Services;

use App\Repositories\Interfaces\ActivityLogRepositoryInterface;

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
        ?array $properties = null
    ) {
        return $this->repository->create([
            'user_id' => auth()->id(),
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'description' => $description,
            'properties' => $properties,
        ]);
    }

    public function latest(int $limit = 10)
    {
        return $this->repository->getLatest($limit);
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }
}