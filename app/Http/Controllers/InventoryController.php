<?php

namespace App\Http\Controllers;

use App\Actions\WooCommerce\Products\UpdateProductAction;
use App\Http\Resources\Inventory\InventoryCollection;
use App\Models\WooCommerce\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InventoryController extends Controller
{
    /**
     * List the inventory
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $products = Product::with('variations', 'variations.parent')
            ->orderBy('date_created', 'desc')
            ->whereStatus('publish')
            ->where('type', '!=', 'variation');

        $products = $this->paginate($request, $products);
        $data = $this->getPaginationResponse($products);

        return Inertia::render('Products/Inventory/Index', [
            ...$data,
            'products' => new InventoryCollection($products->items()),
            '_s' => $request->input('s') ?? '',
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required'
        ]);

        collect($request->products)->map(function ($product) {
            $p = Product::whereProductId($product['product_id']['value'])->first();
            $p->update(['stock_quantity' => (int) $product['stock']['value']]);

            UpdateProductAction::dispatch(
                productId:$p->product_id,
                params: [
                    'stock_quantity' => (int) $product['stock']['value']
                ],
                sync: true
            );
        });

        session()->flash('flash.banner', 'Inventory has updated successfully!');
        session()->flash('flash.bannerStyle', 'success');

        return back();
    }
}
