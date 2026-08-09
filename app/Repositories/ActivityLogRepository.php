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

    public function latest(int $limit = 10)
    {
        return ActivityLog::with('user')
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function create(array $data)
    {
        return ActivityLog::create($data);
    }

    public function find(int $id)
    {
        return ActivityLog::with('user')->findOrFail($id);
    }
}