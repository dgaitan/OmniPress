<?php

namespace App\Http\Controllers;

use App\Services\Analytics\CauseAnalyticsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Analytics/Index');
    }

    public function causes(Request $request)
    {
        $stats = new CauseAnalyticsService();

        return Inertia::render('Analytics/Causes', [
            'stats' => $stats->getSerializedData()
        ]);
    }
}
