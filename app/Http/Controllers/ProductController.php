<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\WooCommerce\Product;

class ProductController extends Controller
{
    public function index(Request $request) {
        $perPage = 50;

        if ($request->input('perPage')) {
            $perPage = $request->input('perPage');
        }

        if ($request->input('s') && !empty($request->input('s'))) {
            $products = Product::searchByKey($request->input('s'));
        } else {
            $products = Product::with(['categories', 'tags', 'images'])->orderBy('date_created', 'desc');
            $products = $products->where('status', 'publish');
        }

        $products = $products->paginate($perPage);
        
        
        return Inertia::render('Products/Index', [
            'products' => collect($products->items())->map(function ($product) {
                return $product->toArray(['withImages' => true]);
            }),
            'total' => $products->total(),
            'nextUrl' => $products->nextPageUrl(),
            'prevUrl' => $products->previousPageUrl(),
            'perPage' => $products->perPage(),
            'currentPage' => $products->currentPage(),
            's' => $request->input('s') ?? '',
            'filterByStatus' => $request->input('filterByStatus') ?? ''
        ]);
    }
}
