<?php

namespace App\Http\Controllers;

use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLogService
    ) {
    }

    /**
     * Menampilkan daftar activity log dengan pencarian & filter.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $action = $request->get('action');

        $activities = $this->activityLogService->searchLogs($search, $action, 15);

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