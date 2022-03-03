<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'perms' => $this->getUserPermissions($request->user()),
            'message' => $request->session()->get('message')
        ]);
    }

    protected function getUserPermissions(User|null $user): array {
        $perms = [];

        if (!is_null($user)) {
            $cacheKey = "user_perms_for_" . $user->id;

            if (Cache::has($cacheKey)) {
                $perms = Cache::get($cacheKey, []);
            } else {
                $perms = Cache::remember(
                    $cacheKey,
                    now()->addDays(7),
                    function() use ($user) {
                        return collect($user->getPermissionsViaRoles())->map(function($p) {
                            return $p->toArray()['name'];
                        })->toArray();
                });
            }
        }

        return $perms;
    }
}
