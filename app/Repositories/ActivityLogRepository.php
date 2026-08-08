<?php

namespace App\Repositories;

use App\Models\ActivityLog;
use App\Repositories\Interfaces\ActivityLogRepositoryInterface;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function all()
    {
        return ActivityLog::with('user')
            ->latest()
            ->get();
    }

    public function paginate(int $perPage = 15)
    {
        return ActivityLog::with('user')
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data)
    {
        return ActivityLog::create($data);
    }

    public function getLatest(int $limit = 10)
    {
        return ActivityLog::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }
}