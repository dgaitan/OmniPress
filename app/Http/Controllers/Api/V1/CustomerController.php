<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WooCommerce\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Retrieve the profile of a customer
     *
     * @param  Request  $request
     * @return array
     */
    public function profile(Request $request)
    {
        // Validate the params
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|integer',
        ]);

        // If validation fails, return error listed with 400 http code
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        $customer = Customer::whereCustomerId($request->customer_id)
            ->with('memberships')
            ->first();

        if (! is_null($customer)) {
            $data = [
                'has_membership' => $customer->hasMemberships(),
                'membership' => null,
            ];

            if ($data['has_membership']) {
                $data['membership'] = $customer->membership()->toArray();
            }

            return response()->json($data, 200);
        }

        return response()->json(['message' => 'Customer not found'], 404);
    }
}
