<?php

namespace App\Http\Controllers\Api\V1;

use App\DTOs\PreOrderDTO;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\PreSales\PreOrderCollection;
use App\Http\Resources\Api\V1\PreSales\PreOrderResource;
use App\Models\PreSales\PreOrder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PreOrderController extends BaseApiController {

    protected string $model = PreOrder::class;
    protected string $resource = PreOrderResource::class;

    /**
     * List PReOrders
     *
     * @param Request $request
     * @return PreOrderCollection
     */
    public function index(Request $request) {
        $preOrders = PreOrder::orderBy('created_at', 'desc')->paginate(50);

        return new PreOrderCollection($preOrders);
    }

    /**
     * Show
     *
     * @param Request $request
     * @param integer $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse {
        return $this->respondShow($request, $id);
    }

    /**
     * Create a PreOrder
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse {
        try {
            $preOrderRequest = PreOrderDTO::fromRequest($request);
            $preOrder = $preOrderRequest->toModel(PreOrder::class);

            return response()->json(new PreOrderResource($preOrder));
        } catch (Throwable $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Update PreORder
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function update(Request $request, int $id) {
        return $this->respondUpdate(
            id: $id,
            callback: function ($preOrder) use ($request) {
                $preOrderRequest = PreOrderDTO::fromRequest($request);
                $preOrder->update($preOrderRequest->toArray());

                return new PreOrderResource($preOrder);
            }
        );
    }
}
