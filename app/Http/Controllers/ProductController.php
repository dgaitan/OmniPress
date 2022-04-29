<?php

namespace App\Http\Controllers;

use App\Exports\Products\ProductSubscriptionExport;
use App\Models\WooCommerce\Product;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

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

        if ($request->has('s') && ! empty($request->s)) {
            $products = Product::search($request->s);
        } else {
            $products = Product::with('categories', 'tags', 'images', 'brands')
                ->where('type', '!=', 'variation');
        }

        // Ordering
        $products = $this->getOrderingQuery(
            request: $request, 
            query: $products, 
            availableOrdering: ['product_id', 'price', 'date_created'], 
            orderBy: 'product_id'
        );

        // Filter By Status
        if ($request->input('status') && 'all' !== $request->input('status')) {
            $status = $request->input('status');
            $products->where('status', $status);
        }

        // $products = $products->where('type', '!=', 'variation');
        $products = $this->paginate($request, $products);
        $data = $this->getPaginationResponse($products);
        $data = array_merge($data, [
            'products' => new ProductCollection($products->items()),
            '_s' => $request->input('s') ?? '',
            '_status' => $status,
            'statuses' => $statuses,
            '_order' => $request->input('order') ?? 'desc',
            '_orderBy' => $request->input('orderBy') ?? ''
        ]);

        return Inertia::render('Products/Index', $data);
    }

    /**
     * Product Detail
     *
     * @param Request $request
     * @param int $id
     * @return void
     */
    public function show(Request $request, $id) {
        $product = Product::with('tags', 'categories', 'brands', 'images')
            ->whereProductId($id)
            ->first();

        if (is_null($product)) {
            abort(404);
        }

        return Inertia::render('Products/Detail', [
            'product' => new ProductResource($product)
        ]);
    }

    public function exportSubscriptions(Request $request) {
        $products = Product::getSubscriptions()->get();
        $data = [];
        $filename = sprintf(
            "kindhumans_product_subscriptions_%s_%s.csv",
            $products->count(),
            now()->format('Y-m-d-H:i:s')
        );

        if ($products->count() > 0) {
            foreach ($products as $product) {
                $data[] = Product::prepareToSubscriptionExport($product);

                if ($product->type === 'variable' && $product->variations->isNotEmpty()) {
                    $data = array_merge(
                        $data,
                        $product->variations->map(fn($p) => Product::prepareToSubscriptionExport($p))->toArray()
                    );
                }
            }
        }

        return Excel::download(
            new ProductSubscriptionExport($data),
            $filename,
            \Maatwebsite\Excel\Excel::CSV,
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }
}
