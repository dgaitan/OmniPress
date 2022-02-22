<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\WooCommerce\Product;

class ProductController extends Controller
{
    /**
     * Product Index View
     *
     * @param Request $request
     * @return array
     */
    public function index(Request $request) {
        $statuses = [
            ['slug' => 'publish', 'label' => 'Publish'],
            ['slug' => 'draft', 'label' => 'Draft'],
        ];
        $status = '';
        $products = Product::with('categories', 'tags', 'images');

        // Ordering
        $availableOrders = ['product_id', 'price', 'date_created'];
        if ($request->input('orderBy') && in_array($request->input('orderBy'), $availableOrders)) {
            $ordering = in_array($request->input('order'), ['desc', 'asc'])
                ? $request->input('order')
                : 'desc';

            $products->orderBy($request->input('orderBy'), $ordering);
        } else {
            $products->orderBy('date_created', 'desc');
        }

        // Search
        $search = $this->analyzeSearchQuery($request, ['product_id', 'status', 'type']);
        if ($search->isValid) {
            // If the search query isn't specific
            if (! $search->specific) {
                $s = $search->s;
                $products->orWhere('name', 'ilike', "%$s%");
                $products->orWhere('price', 'ilike', "%$s%");
                $products->orWhere('sku', 'ilike', "%$s%");
            } else {
                $products->where($search->key, 'ilike', "$search->s%");
            }
        }

        // Filter By Status
        if ($request->input('status') && 'all' !== $request->input('status')) {
            $status = $request->input('status');
            $products->where('status', $status);
        }

        $products = $products->where('type', '!=', 'variation');
        $products = $this->paginate($request, $products);
        $data = $this->getPaginationResponse($products);
        $data = array_merge($data, [
            'products' => collect($products->items())->map(function ($product) {
                return $product->toArray(['withImages' => true]);
            }),
            '_s' => $request->input('s') ?? '',
            '_status' => $status,
            'statuses' => $statuses,
            '_order' => $request->input('order') ?? 'desc',
            '_orderBy' => $request->input('orderBy') ?? ''
        ]);

        return Inertia::render('Products/Index', $data);
    }
}
