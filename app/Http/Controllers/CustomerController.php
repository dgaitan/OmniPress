<?php

namespace App\Http\Controllers;

use App\Models\WooCommerce\Customer;
use App\Http\Resources\CustomerCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class CustomerController extends Controller
{
    /**
     * Index View
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request) {
        $roles = Customer::getRoles();
        $role = '';
        $customers = null;
        $cacheKey = 'customer_lists';

        // Ordering
        $availableOrdering = [
            'customer_id', 'first_name', 'username',
            'email', 'date_created'
        ];

        if (
            $request->input('orderBy')
            && in_array($request->input('orderBy'), $availableOrdering)
        ) {
            $ordering = in_array($request->input('order'), ['desc', 'asc'])
                ? $request->input('order')
                : 'desc';

            $customers = Customer::orderBy($request->input('orderBy'), $ordering);
            $cacheKey = sprintf(
                '%s_order_by_%s_%s',
                $cacheKey,
                $request->input('orderBy'),
                $ordering
            );
        } else {
            $customers = Customer::orderBy('date_created', 'desc');
            $cacheKey = $cacheKey . '_order_by_date_created_desc';
        }

        // Search
        $search = $this->analyzeSearchQuery(
            $request,
            ['customer_id', 'first_name', 'last_name', 'email', 'username']
        );

        if ($search->isValid) {
            // If the search query isn't specific
            if (!$search->specific) {
                $s = $search->s;

                $customers->orWhere('customer_id', 'ilike', "%$s%");
                $customers->orWhere('first_name', 'ilike', "%$s%");
                $customers->orWhere('last_name', 'ilike', "%$s%");
                $customers->orWhere('email', 'ilike', "%$s%");
                $customers->orWhere('username', 'ilike', "%$s%");
                $cacheKey = sprintf('%s_search_by_%s', $cacheKey, $s);
            } else {
                $customers->where($search->key, 'ilike', "$search->s%");
                $cacheKey = sprintf(
                    '%s_search_by_%s_%s', $cacheKey, $search->key, $search->s
                );
            }
        }

        // Filter By Role
        if ($request->input('role') && 'all' !== $request->input('role')) {
            $role = $request->input('role');
            $customers->where('role', $role);
            $cacheKey = sprintf(
                '%s_filtered_by_status_%s', $cacheKey, $role
            );
        }

        // if (Cache::tags('customers')->has($cacheKey)) {
        //     $customers = Cache::tags('customers')->get($cacheKey, []);
        // } else {
        //     $customers = Cache::tags('customers')
        //         ->remember($cacheKey, 3600, function () use ($customers, $request) {
        //         return $this->paginate($request, $customers);
        //     });
        // }

        $customers = $this->paginate($request, $customers);
        $data = $this->getPaginationResponse($customers);
        $data['customers'] = new CustomerCollection($customers);
        $data['roles'] = $roles;
        $data = array_merge($data, [
            'customers' => new CustomerCollection($customers),
            '_s' => $request->input('s') ?? '',
            'roles' => $roles,
            '_role' => $role,
            '_order' => $request->input('order') ?? 'desc',
            '_orderBy' => $request->input('orderBy') ?? ''
        ]);

        return Inertia::render('Customers/Index', $data);
    }
}
