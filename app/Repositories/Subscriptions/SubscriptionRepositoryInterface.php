<?php

namespace App\Repositories\Subscriptions;

use Illuminate\Http\Request;

interface SubscriptionRepositoryInterface
{
    public function list(Request $request, bool $paginate = true);
    public function show(int|string $id);
    public function create(array $params);
    public function update(int|string $id, array $params);
}
