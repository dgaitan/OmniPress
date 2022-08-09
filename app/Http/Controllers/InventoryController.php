<?php

namespace App\Http\Controllers;

use App\Actions\WooCommerce\Products\UpdateProductAction;
use App\Http\Resources\Inventory\InventoryCollection;
use App\Models\WooCommerce\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    /**
     * List the inventory
     *
     * @param  Request  $request
     * @return void
     */
    public function index(Request $request)
    {
        $products = Product::with('variations', 'variations.parent')
            ->orderBy('date_created', 'desc')
            ->whereStatus('publish')
            ->where('type', '!=', 'variation');

        if ($request->has('s')) {
            $s = $request->input('s');
            $products->where(function ($query) use ($s) {
                return $query->where('name', 'ilike', "%$s%")
                    ->orWhere('product_id', 'ilike', "%$s")
                    ->orWhere('sku', 'ilike', "%$s%");
            });
        }

        $products = $this->paginate($request, $products);
        $data = $this->getPaginationResponse($products);

        return Inertia::render('Products/Inventory/Index', [
            ...$data,
            'products' => new InventoryCollection($products->items()),
            '_s' => $request->input('s') ?? '',
        ]);
    }

    /**
     * Update Inventory
     *
     * @param  Request  $request
     * @return void
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'products' => 'required',
        ]);

        collect($request->products)->map(function ($product) {
            $p = Product::whereProductId($product['product_id']['value'])->first();
            $p->update(['stock_quantity' => (int) $product['stock']['value']]);

            UpdateProductAction::dispatch(
                product: $p,
                params: [
                    'stock_quantity' => (int) $product['stock']['value'],
                ],
                sync: true
            );
        });

        session()->flash('flash.banner', 'Inventory has updated successfully!');
        session()->flash('flash.bannerStyle', 'success');

        return back()->banner('Inventory has updated successfully!');
    }
}
