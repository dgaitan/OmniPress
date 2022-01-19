<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WooCommerce\Customer;

class CustomerController extends Controller
{
    public function index() {
        return Customer::take(10)->get();
    }
}
