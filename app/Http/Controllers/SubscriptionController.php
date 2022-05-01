<?php

namespace App\Http\Controllers;

use App\Models\Subscription\KindhumanSubscription;
use App\Repositories\Subscriptions\SubscriptionRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    /**
     * Undocumented function
     *
     * @param SubscriptionRepositoryInterface $subscriptionRepository
     */
    public function __construct(
        private SubscriptionRepositoryInterface $subscriptionRepository
    ) {}

    /**
     * Subscription List View
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $subscription = $this->subscriptionRepository->list(request: $request);

        $response = [
            ...$this->subscriptionRepository->getPaginationResponse(pagination: $subscription),
            'data' => $this->subscriptionRepository->serializeCollection(data: $subscription),
            'statuses' => KindhumanSubscription::getStatuses(),
            '_status' => $request->input('status') ?? ''
        ];

        return Inertia::render('Subscriptions/Index', $response);
    }
}
