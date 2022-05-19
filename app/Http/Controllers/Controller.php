<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Scout\Builder as ScoutBuilder;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Execute Pagination Query
     *
     * @param Request $request
     * @param [type] $query
     * @return void
     */
    protected function paginate(
        Request $request,
        Builder|ScoutBuilder $query
    ): LengthAwarePaginator {
        $perPage = 50;

        if ($request->input('perPage')) {
            $perPage = $request->input('perPage');
        }

        return $query->paginate($perPage);
    }

    /**
     * Get Pagination Response
     *
     * @param [type] $pagination
     * @return array
     */
    protected function getPaginationResponse($pagination): array {
        return [
            'total' => $pagination->total(),
            'nextUrl' => $pagination->nextPageUrl(),
            'prevUrl' => $pagination->previousPageUrl(),
            '_perPage' => $pagination->perPage(),
            '_currentPage' => $pagination->currentPage(),
        ];
    }

    /**
     * Analyze the search query, to know if the user
     * is filtering by an specific key or column.
     *
     * Ex: title: Foo Bar
     *
     * @param Request $request
     * @param array $keys
     * @return object
     */
    protected function analyzeSearchQuery(Request $request, array $keys = []): object {
        $data = (object) [
            's' => $request->input('s'),
            'isValid' => $request->input('s') && ! empty( $request->input('s') ),
            'specific' => false,
            'key' => '',
            'segments' => explode(':', $request->input('s'))
        ];

        if ($data->isValid && count($data->segments) === 2) {
            $data->key = $data->segments[0];

            if (! in_array($data->key, $keys)) return $data;

            $data->specific = true;
            $data->s = trim($data->segments[1]);
        }

        return $data;
    }

    /**
     * Process Ordering Query
     *
     * @param Request $request
     * @param Builder|ScoutBuilder $query
     * @param array $availableOrdering
     * @param string $orderBy
     * @return Builder|ScoutBuilder
     */
    protected function getOrderingQuery(
        Request $request,
        Builder|ScoutBuilder $query,
        array $availableOrdering,
        string $orderBy = 'id'
    ): Builder|ScoutBuilder {
        $ordering = 'desc';

        if (
            ! $request->has('orderBy') &&
            ! in_array($request->input('orderBy'), $availableOrdering)
        ) {
            return $query;
        }

        $ordering = in_array($request->input('order'), ['desc', 'asc'])
            ? $request->input('order')
            : 'desc';
        $orderBy = $request->input('orderBy');

        if ($query instanceof ScoutBuilder) {
            $query->query(function($q) use ($ordering, $orderBy) {
                $q->orderBy($orderBy, $ordering);
            });
        } else {
            $query->orderBy($orderBy, $ordering);
        }

        return $query;
    }

    protected function addFilterToQuery(
        Request $request,
        string $filterKey,
        Builder|ScoutBuilder $query,
        array $validFilterKeys = []
    ): Builder|ScoutBuilder {
        if (! $request->has($filterKey) && empty($request->input($filterKey))) {
            return $query;
        }

        if (! in_array($request->input($filterKey), $validFilterKeys)) {
            return $query;
        }

        if ($query instanceof ScoutBuilder) {
            $query->query(function ($q) use ($filterKey, $request) {
                $q->where($filterKey, $request->input($filterKey));
            });
        } else {
            $query->where($filterKey, $request->input($filterKey));
        }

        return $query;
    }
}
