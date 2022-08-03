<?php

namespace App\Http\Controllers;

use App\Http\Resources\Inventory\InventoryCollection;
use App\Models\WooCommerce\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::with('variations', 'variations.parent')
            ->orderBy('date_created', 'desc')
            ->whereStatus('publish')
            ->where('type', '!=', 'variation')
            ->paginate(50);
        
        return Inertia::render('Products/Inventory/Index', [
            'products' => new InventoryCollection($products)
        ]);
    }
}
