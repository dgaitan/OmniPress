<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WooCommerce\Customer;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index() {
        return Inertia::render('Customers/Index', [
            'customers' => Customer::take(40)->orderBy('customer_id', 'desc')->get()
        ]);
    }
}
