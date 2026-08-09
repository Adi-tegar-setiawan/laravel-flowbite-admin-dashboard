<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct(
        protected DashboardService $dashboardService
    ) {
    }

    /**
     * Menampilkan dashboard utama admin.
     */
    public function index(): View
    {
        $dashboard = $this->dashboardService->getDashboardData();

        return view('dashboard', [
            'dashboard' => $dashboard,
        ]);
    }
}