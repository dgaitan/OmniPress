<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Cashier\Exceptions\IncompletePayment;
use App\Http\Controllers\Controller;
use App\Models\WooCommerce\Customer;

class PaymentController extends Controller
{
    /**
     * [intent description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function intent(Request $request) {
        try {
            // Validate the params
            $validator = Validator::make($request->all(), [
                'amount' => 'required|integer',
                'token' => 'required|string',
                'customer_id' => 'nullable|integer',
                'customer_email' => 'nullable|email:rfc,dns',
                'customer_username' => 'nullable|string',
                'description' => 'required|string',
                'save' => 'nullable|boolean'
            ]);

            // If validation fails, return error listed with 400 http code
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 400);
            }

            // First create an instance of a customer just to make a simple charge.
            $customer = new Customer;
            $defaultCard = [];

            // If is there present a customer id, let's create or retrieve it
            if ($request->customer_id) {
                $customer = Customer::firstOrCreate([
                    'customer_id' => $request->customer_id,
                    'email'       => $request->customer_email,
                    'username'    => $request->customer_username
                ]);

                if ($request->save) {
                    $customer->createOrGetStripeCustomer();
                    $customer->addPaymentMethod($request->token);

                    $defaultCard = $customer->findPaymentMethod($request->token)->id;
                    $defaultCard = $customer->updateDefaultPaymentMethod($defaultCard);

                    $defaultCard = [
                        'brand' => $defaultCard->card->brand,
                        'exp_month' => $defaultCard->card->exp_month,
                        'exp_year' => $defaultCard->card->exp_year,
                        'last4' => $defaultCard->card->last4
                    ];
                }
            }
            
            $customer->charge($request->amount, $request->token, [
                'description' => $request->description
            ]);

            return response()->json([
                'status' => 'succeeded',
                'default_card' => $defaultCard
            ], 200);

        } catch (IncompletePayment $exception) {
            return response()->json(['status' => $exception->payment->status], 200);
        }
    }

    /**
     * [direct description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function direct(Request $request) {
        try {
            // Validate the params
            $validator = Validator::make($request->all(), [
                'amount' => 'required|integer',
                'description' => 'required|string',
                'customer_id' => 'required|integer'
            ]);

            // If validation fails, return error listed with 400 http code
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 400);
            }

            $customer = Customer::whereCustomerId(
                $request->customer_id
            )->first();

            if (!$customer->hasDefaultPaymentMethod()) {
                return response()->json([
                    'status' => 'failed', 
                    'error' => 'Customer has not default payment method'
                ], 200);
            }

            $customer->charge(
                $request->amount, 
                $customer->defaultPaymentMethod()->id, 
                ['description' => $request->description]
            );

            return response()->json(['status' => 'succeeded'], 200);


        } catch (IncompletePayment $exception) {
            return response()->json([
                'status' => $exception->payment->status
            ], 200);
        }
    }

    /**
     * [paymentMethods description]
     * @param  Request $request     [description]
     * @param  [type]  $customer_id [description]
     * @return [type]               [description]
     */
    public function paymentMethods(Request $request, $customer_id) {
        $customer = Customer::whereCustomerId($customer_id)->first();
        $defaultMethod = "";

        if (is_null($customer)) {
            return response()->json(['message' => 'Customer not found'], 404);
        }

        if ($customer->hasDefaultPaymentMethod()) {
            $defaultMethod = $customer->defaultPaymentMethod()->id;
        }

        return response()->json($customer->paymentMethods()->map(function ($method) use ($defaultMethod) {
            $m = $method->toArray();
            $m['is_default'] = $m['id'] === $defaultMethod;

            return $m;
        }));
    }
}
