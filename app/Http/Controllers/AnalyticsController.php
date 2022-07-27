<?php

namespace App\Http\Controllers;

use App\Services\Analytics\CauseAnalyticsService;
use App\Services\Analytics\Period;
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
        $period = $request->has('filterBy')
            ? $request->input('filterBy')
            : 'month_to_date';
        $stats = new CauseAnalyticsService($period);

        return Inertia::render('Analytics/Causes', [
            'stats' => $stats->getSerializedData(cached: false),
            'periods' => Period::VALID_PERIODS,
            'currentPeriod' => $period,
        ]);
    }
}
