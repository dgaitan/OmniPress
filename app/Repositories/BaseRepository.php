<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Laravel\Scout\Builder as ScoutBuilder;

abstract class BaseRepository
{
    /**
     * Resource Class
     *
     * @var string
     */
    protected string $jsonResourceClass;

    /**
     * JsonCollectionClass
     *
     * @var string
     */
    public static string $jsonCollectionClass;

    /**
     * Undocumented function
     *
     * @param Collection|LengthAwarePaginator $data
     * @return void
     */
    public function serializeCollection(Collection|LengthAwarePaginator $data)
    {
        return new static::$jsonCollectionClass($data);
    }

    /**
     * Execute Pagination Query
     *
     * @param int|null $perPage
     * @param [type] $query
     * @return void
     */
    protected function paginate(
        int|null $perPage,
        Builder|ScoutBuilder $query
    ): LengthAwarePaginator
    {
        if (is_null($perPage)) {
            $perPage = 50;
        }

        return $query->paginate($perPage);
    }

    /**
     * Get Pagination Response
     *
     * @param LengthAwarePaginator $pagination
     * @return array
     */
    public function getPaginationResponse(LengthAwarePaginator $pagination): array
    {
        return [
            'total' => $pagination->total(),
            'nextUrl' => $pagination->nextPageUrl(),
            'prevUrl' => $pagination->previousPageUrl(),
            '_perPage' => $pagination->perPage(),
            '_currentPage' => $pagination->currentPage(),
        ];
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

        if ($request->input('orderBy') && in_array($request->input('orderBy'), $availableOrdering)) {
            $ordering = in_array($request->input('order'), ['desc', 'asc'])
                ? $request->input('order')
                : 'desc';
            $orderBy = $request->input('orderBy');
        }

        if ($request->has('s') && $query instanceof ScoutBuilder) {
            $query = $query->query(function($q) use ($ordering, $orderBy) {
                $q->orderBy($orderBy, $ordering);
            });
        } else {
            $query->orderBy($orderBy, $ordering);
        }

        return $query;
    }
}
