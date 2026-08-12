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

    public function search(?string $search = null, ?string $action = null, int $perPage = 15)
    {
        $query = ActivityLog::with('user')->latest(); // Asumsi nama model ActivityLog

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                ->orWhereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($action) {
            $query->where('action', $action);
        }

        return $query->paginate($perPage);
    }
}