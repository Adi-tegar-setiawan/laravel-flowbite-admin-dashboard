<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;

class DashboardController extends Controller
{
    /**
     * Constructor
     */
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    /**
     * Menampilkan dashboard.
     */
    public function index()
    {
        $dashboard = $this->dashboardService->getDashboardData();

        return view('dashboard', [
            'dashboard' => $dashboard,
        ]);
    }
}