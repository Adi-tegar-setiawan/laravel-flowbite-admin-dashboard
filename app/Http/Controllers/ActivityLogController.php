<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;

class ActivityLogController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Menampilkan daftar activity log.
     */
    public function index()
    {
        $activities = $this->activityLogService->all();

        return view('activity-logs.index', compact('activities'));
    }

    /**
     * Menampilkan detail activity log.
     */
    public function show(int $id)
    {
        $activity = $this->activityLogService->find($id);

        return view('activity-logs.show', compact('activity'));
    }
}