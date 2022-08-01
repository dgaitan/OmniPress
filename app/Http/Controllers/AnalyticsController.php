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

    /**
     * Return Causes Analytics
     *
     * @param Request $request
     * @return void
     */
    public function causes(Request $request)
    {
        $params = $this->getParams($request);
        $stats = new CauseAnalyticsService($params->period);

        return Inertia::render('Analytics/Causes', [
            'stats' => $stats->getSerializedData(cached: false, perPage: $params->perPage),
            'periods' => Period::VALID_PERIODS,
            'currentPeriod' => $params->period,
            'perPage' => $params->perPage
        ]);
    }

    /**
     * Get Analytics Params
     *
     * @param Request $request
     * @return object
     */
    protected function getParams(Request $request): object {
        return (object) [
            'period' => $request->has('filterBy')
                ? $request->input('filterBy')
                : 'month_to_date',
            'perPage' => $request->has('perPage')
                ? $request->input('perPage')
                : 10
        ];
    }
}
