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
}
