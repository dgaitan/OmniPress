<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    /**
     * Users List
     *
     * @param  Request  $request
     * @return void
     */
    public function index(Request $request)
    {
        $roles = [
            'super_admin' => 'Super Admin',
            'admin' => 'Admin',
            'kindhumans_team' => 'Kindhumans Team',
        ];
        $role = '';
        $users = User::with('roles');

        // Ordering
        $availableOrders = ['user_id', 'date_created', 'email', 'name'];
        if ($request->input('orderBy') && in_array($request->input('orderBy'), $availableOrders)) {
            $ordering = in_array($request->input('order'), ['desc', 'asc'])
                ? $request->input('order')
                : 'desc';

            $users->orderBy($request->input('orderBy'), $ordering);
        } else {
            $users->orderBy('created_at', 'desc');
        }

        // Search
        $search = $this->analyzeSearchQuery($request, ['user_id', 'email', 'name']);
        if ($search->isValid) {
            // If the search query isn't specific
            if (! $search->specific) {
                $s = $search->s;
                $users->orWhereHas('customer', function ($query) use ($s) {
                    $query->where('first_name', 'ilike', "%$s%")
                        ->orWhere('last_name', 'ilike', "%$s%")
                        ->orWhere('email', 'ilike', "%$s%")
                        ->orWhere('username', 'ilike', "%$s%");
                });
                $users->orWhere('name', 'ilike', "%$s%")
                    ->orWhere('email', 'ilike', "%$s%")
                    ->orWhere('id', 'ilike', "%$s%");
            } else {
                $users->where($search->key, 'ilike', "$search->s%");
            }
        }

        // Filter By Role
        if ($request->input('status') && 'all' !== $request->input('status')) {
            $role = $request->input('role');
            $users = $users->role($role);
        }

        $users = $this->paginate($request, $users);
        $data = $this->getPaginationResponse($users);
        $data = array_merge($data, [
            'users' => $users->map(function ($user) {
                return $user->toArray();
            }),
            '_s' => $request->input('s') ?? '',
            '_status' => $role,
            'statuses' => $roles,
            '_order' => $request->input('order') ?? 'desc',
            '_orderBy' => $request->input('orderBy') ?? '',
        ]);

        return Inertia::render('Users/Index', $data);
    }
}
