<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WooCommerce\Customer;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Laravel\Cashier\Exceptions\IncompletePayment;

class PaymentController extends Controller
{
    /**
     * [intent description]
     *
     * @param  Request  $request [description]
     * @return [type]           [description]
     */
    public function intent(Request $request)
    {
        try {
            // Validate the params
            $validator = Validator::make($request->all(), [
                'amount' => 'required|integer',
                'token' => 'required|string',
                'customer_id' => 'nullable|integer',
                'customer_email' => 'nullable|email',
                'customer_username' => 'nullable|string',
                'description' => 'required|string',
                'save' => 'nullable|boolean',
            ]);

            // If validation fails, return error listed with 400 http code
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            // First create an instance of a customer just to make a simple charge.
            $customer = new Customer;
            $defaultCard = [];

            // If is there present a customer id, let's create or retrieve it
            if ($request->customer_id) {
                $customer = Customer::firstOrNew([
                    'customer_id' => $request->customer_id,
                    'email' => $request->customer_email,
                ]);

                if (! $customer->username) {
                    $customer->username = $request->customer_username;
                }

                $customer->save();

                if ($request->save) {
                    if (is_null($customer->stripe_id)) {
                        $customer->createOrGetStripeCustomer();
                    }

                    try {
                        $customer->findPaymentMethod($request->token);
                    } catch (\Laravel\Cashier\Exceptions\InvalidPaymentMethod $e) {
                        $defaultCard = $customer->addAndAssignDefaultPaymentMethod($request->token);
                    }
                }
            }

            $paymentIntent = $customer->charge($request->amount, $request->token, [
                'description' => $request->description,
            ]);

            return response()->json([
                'status' => 'succeeded',
                'payment_intent_id' => $paymentIntent->id,
                'card' => $defaultCard ? $defaultCard['card'] : [],
            ], 200);
        } catch (IncompletePayment $exception) {
            return response()->json([
                'status' => $exception->payment->status,
                'error' => serialize($exception),
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * [direct description]
     *
     * @param  Request  $request [description]
     * @return [type]           [description]
     */
    public function direct(Request $request)
    {
        try {
            // Validate the params
            $validator = Validator::make($request->all(), [
                'amount' => 'required|integer',
                'description' => 'required|string',
                'customer_id' => 'required|integer',
            ]);

            // If validation fails, return error listed with 400 http code
            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors(),
                ], 400);
            }

            $customer = Customer::whereCustomerId(
                $request->customer_id
            )->first();

            if (! $customer->hasDefaultPaymentMethod()) {
                return response()->json([
                    'status' => 'failed',
                    'error' => 'Customer has not default payment method',
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
                'status' => $exception->payment->status,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => $e->getMessage(),
            ], 200);
        }
    }

    /**
     * [addPaymentMethod description]
     *
     * @param  Request  $request [description]
     */
    public function addPaymentMethod(Request $request)
    {
        // Validate the params
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'customer_id' => 'required|integer',
        ]);

        // If validation fails, return error listed with 400 http code
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        // If the customer is an array it means that it wasn't found.
        if (is_array($customer = $this->getCustomer($request->customer_id))) {
            return response()->json($customer, 404);
        }

        if (! $customer->stripeId()) {
            $customer->createOrGetStripeCustomer();
        }

        $card = $customer->addAndAssignDefaultPaymentMethod($request->token);

        return response()->json([
            'status' => 'succeeded',
            'result' => $card,
        ]);
    }

    /**
     * [deletePaymentMethod description]
     *
     * @param  Request  $request [description]
     * @return [type]           [description]
     */
    public function deletePaymentMethod(Request $request)
    {
        // Validate the params
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'customer_id' => 'required|integer',
            'is_default' => 'required|boolean',
        ]);

        // If validation fails, return error listed with 400 http code
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        // If the customer is an array it means that it wasn't found.
        if (is_array($customer = $this->getCustomer($request->customer_id))) {
            return response()->json($customer, 404);
        }

        // Deleting payment method
        $customer->findPaymentMethod($request->token)->delete();

        if (! $request->is_default) {
            return response()->json([
                'status' => 'succeeded',
            ], 200);
        }

        // But we need to assign a new default payment method if there's another one
        $paymentMethod = $customer->paymentMethods()->first();

        if ($paymentMethod) {
            $customer->updateDefaultPaymentMethod($paymentMethod->id);

            return response()->json(
                $this->prepareResponseData($paymentMethod),
                200
            );
        }

        return response()->json([
            'status' => 'succeeded',
        ], 200);
    }

    /**
     * [setDefaultPaymentMethod description]
     *
     * @param  Request  $request [description]
     */
    public function setDefaultPaymentMethod(Request $request)
    {
        // Validate the params
        $validator = Validator::make($request->all(), [
            'token' => 'required|string',
            'customer_id' => 'required|integer',
        ]);

        // If validation fails, return error listed with 400 http code
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        // If the customer is an array it means that it wasn't found.
        if (is_array($customer = $this->getCustomer($request->customer_id))) {
            return response()->json($customer, 404);
        }

        $customer->updateDefaultPaymentMethod($request->token);

        return response()->json(
            $this->prepareResponseData($customer->defaultPaymentMethod()),
            200
        );
    }

    /**
     * [getDefaultPaymentMethod description]
     *
     * @param  Request  $request [description]
     * @return [type]           [description]
     */
    public function getDefaultPaymentMethod(Request $request, $customer_id)
    {

        // If validation fails, return error listed with 400 http code
        if (! $customer_id) {
            return response()->json([
                'errors' => ['Customer Id is required'],
            ], 400);
        }

        // If the customer is an array it means that it wasn't found.
        if (is_array($customer = $this->getCustomer($customer_id))) {
            return response()->json($customer, 404);
        }

        $paymentMethod = $customer->defaultPaymentMethod();

        return response()->json([
            'card' => $paymentMethod
                ? Customer::getCardResume($paymentMethod)
                : [],
        ], 200);
    }

    /**
     * [paymentMethods description]
     *
     * @param  Request  $request     [description]
     * @param  [type]  $customer_id [description]
     * @return [type]               [description]
     */
    public function paymentMethods(Request $request, $customer_id)
    {
        $customer = Customer::whereCustomerId($customer_id)->first();
        $defaultMethod = '';

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

    /**
     * [getCustomer description]
     *
     * @param  int  $customerId [description]
     * @return [type]             [description]
     */
    protected function getCustomer($customerId)
    {
        $customer = Customer::whereCustomerId((int) $customerId)->first();

        if (is_null($customer)) {
            return [
                'status' => 'failed',
                'error' => 'Customer not found',
            ];
        }

        return $customer;
    }

    /**
     * [prepareResponseData description]
     *
     * @param  [type]  $paymentMethod [description]
     * @param  bool  $isDefault     [description]
     * @return [type]                 [description]
     */
    protected function prepareResponseData($paymentMethod, $isDefault = true)
    {
        $card = Customer::getCardResume($paymentMethod);
        $paymentMethod = $paymentMethod->toArray();

        if ($isDefault) {
            $paymentMethod['is_default'] = true;
        }

        return [
            'status' => 'succeeded',
            'card' => $card,
            'payment_method' => $paymentMethod,
        ];
    }
}
