<?php

namespace App\Http\Controllers;

use App\Http\Resources\Inventory\InventoryCollection;
use App\Models\WooCommerce\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::orderBy('date_created', 'desc')
            ->where('status', 'publish')
            ->paginate(50);
        
        return Inertia::render('Products/Inventory/Index', [
            'products' => new InventoryCollection($products)
        ]);
    }
}
