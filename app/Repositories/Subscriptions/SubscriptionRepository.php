<?php

namespace App\Repositories\Subscriptions;

use App\Http\Resources\Subscription\SubscriptionCollection;
use App\Models\Subscription\KindhumanSubscription as Subscription;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SubscriptionRepository extends BaseRepository implements SubscriptionRepositoryInterface
{
    /**
     * JsonCollectionClass
     *
     * @var string
     */
    public static string $jsonCollectionClass = SubscriptionCollection::class;

    /**
     * Retrieve the list data view
     *
     * @param Request $request
     * @return void
     */
    public function list(Request $request, bool $paginate = true)
    {
        $query = Subscription::with(['items', 'items.product', 'customer'])
            ->whereNotNull('next_payment_date')
            ->whereNotNull('payment_interval')
            ->orderBy('next_payment_date', 'asc');

        $query = $this->queryset(query: $query, request: $request);
        $subscriptions = $paginate
            ? $this->paginate(perPage: 50, query: $query)
            : $query->get();

        return $subscriptions;
    }

    public function show(int|string $id)
    {

    }

    public function create(array $params)
    {

    }

    public function update(int|string $id, array $params)
    {

    }

    /**
     * Build QuerySet
     *
     * @param Builder $query
     * @param Request $request
     * @return Builder
     */
    protected function queryset(Builder $query, Request $request): Builder
    {
        // Filter By Status
        if ($request->input('status') && 'all' !== $request->input('status')) {
            $query->where('status', $request->input('status'));
        }

        $query = $this->getOrderingQuery(
            request: $request,
            query: $query,
            availableOrdering: ['id', 'start_date', 'next_payment_date', 'last_payment'],
            orderBy: 'next_payment_date'
        );

        return $query;
    }
}
