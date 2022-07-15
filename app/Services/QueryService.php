<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class QueryService
{
    public static function walkTrough(Builder $query, $callback)
    {
        $offset = 0;
        $perPage = 100;
        $page = 1;
        $items = $query->skip($offset)->take($perPage)->get();

        while ($items->isNotEmpty()) {
            $page++;

            $items->map($callback);

            $offset = $offset + $perPage;
            $items = $query->skip($offset)->take($perPage)->get();
        }
    }
}
