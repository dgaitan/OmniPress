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
        $stats = new CauseAnalyticsService(
            period: $params->period, from: $params->fromDate, to: $params->toDate
        );

        return Inertia::render('Analytics/Causes', [
            'stats' => $stats->getSerializedData(cached: false, perPage: $params->perPage),
            'periods' => Period::VALID_PERIODS,
            ...(array) $params
        ]);
    }

    /**
     * Get Analytics Params
     *
     * @param Request $request
     * @return object
     */
    protected function getParams(Request $request): object
    {
        $data = [
            'filterType' => $this->validateFilterType($request),
            'period' => $request->has('filterBy')
                ? $request->input('filterBy')
                : 'month_to_date',
            'perPage' => $request->has('perPage')
                ? $request->input('perPage')
                : 10,
            'fromDate' => $request->has('fromDate')
                ? $request->input('fromDate')
                : '',
            'toDate' => $request->has('toDate')
                ? $request->input('toDate')
                : ''
        ];

        if ($data['filterType'] === 'custom') {
            $data['period'] = 'custom';
        }

        return (object) $data;
    }

    protected function validateFilterType(Request $request): string
    {
        $filterTypes = ['preset', 'custom'];

        if (! $request->has('filterType') || ! in_array($request->input('filterType'), $filterTypes)) {
            return 'preset';
        }

        return $request->input('filterType');
    }
}
