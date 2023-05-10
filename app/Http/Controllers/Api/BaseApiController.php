<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class BaseApiController extends Controller {

    /**
     * Mode instance to manipulate
     *
     * @var string
     */
    protected string $model;

    /**
     * Resource Instance
     *
     * @var string
     */
    protected string $resource;

    /**
     * Respond Show
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function respondShow(Request $request, int $id): JsonResponse {
        try {
            $instance = $this->model::findOrFail($id);
            return response()->json(new $this->resource($instance));
        } catch (Throwable $e) {
            return response()->json([
                'message' => 'Element Not Found'
            ], 404);
        }
    }

    public function respondUpdate(int $id, Closure $callback) {
        try {
            $instance = $this->model::findOrFail($id);
            $data = $callback($instance);

            return response()->json($data);
        } catch (Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
