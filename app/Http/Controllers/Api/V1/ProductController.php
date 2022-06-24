<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WooCommerce\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;

        if ($request->input('perPage')) {
            $perPage = $request->input('perPage');
        }

        if ($request->input('s') && ! empty($request->input('s'))) {
            $products = Product::searchByKey($request->input('s'));
        } else {
            $products = Product::with(['categories', 'tags', 'images'])->orderBy('date_created', 'desc');
            $products = $products->where('status', 'publish');
        }

        $products = $products->paginate($perPage);

        return response()->json([
            'data' => collect($products->items())->map(function ($product) {
                return $product->toArray(['withImages' => true]);
            }),
            'total' => $products->total(),
            'nextUrl' => $products->nextPageUrl(),
            'prevUrl' => $products->previousPageUrl(),
            'perPage' => $products->perPage(),
            'currentPage' => $products->currentPage(),
        ]);
    }
}
