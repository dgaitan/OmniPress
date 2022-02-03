<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getPaginationResponse($pagination): array {
        return [
            'total' => $pagination->total(),
            'nextUrl' => $pagination->nextPageUrl(),
            'prevUrl' => $pagination->previousPageUrl(),
            'perPage' => $pagination->perPage(),
            'currentPage' => $pagination->currentPage(),
        ];
    }
}
