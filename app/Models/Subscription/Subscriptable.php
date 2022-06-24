<?php

namespace App\Models\Subscription;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\Builder;

trait Subscriptable
{
    /**
     * Undocumented function
     *
     * @return bool
     */
    public function isSubscription(): bool
    {
        return $this->isVariation()
            ? $this->parent->isSubscription()
            : $this->getMetaValue('_kindhumans_subscription', 'no') === 'yes';
    }

    /**
     * Sync a subscription date.
     *
     * @return void
     */
    public function syncSubscription()
    {
        // First we need to know if this product is a subscription product.
        if (! $this->isSubscription()) {
            return false;
        }

        $this->has_subscription = true;

        $data = [
            'expiration_date' => $this->getMetaValue('_kh_expiration_date')
                ? Carbon::parse($this->getMetaValue('_kh_expiration_date'))
                : null,
            'price' => (int) ((float) $this->getMetaValue('_ks_recurring_price', 0) * 100),
            'fee' => (int) ((float) $this->getMetaValue('_ks_subscription_fee', 0) * 100),
            'intervals' => unserialize($this->getMetaValue('_kh_period_intervals', '')),
        ];

        if ($this->isVariation()) {
            $data['use_parent_settings'] = $data['price'] !== 0 && $data['fee'] !== 0;
        } else {
            $data['use_parent_settings'] = false;
        }

        if (! $this->subscription) {
            $this->subscription()->create($data);
        } else {
            $this->subscription->update($data);
        }

        $this->save();
    }

    /**
     * Get Subscriptions
     *
     * @return Builder
     */
    public static function getSubscriptions(): Builder
    {
        return self::whereHasSubscription(true)
            ->where('type', '!=', 'variation');
    }

    public static function prepareToSubscriptionExport($product): array
    {
        $intervals = '';

        if ($product->subscription->intervals) {
            $intervals = collect($product->subscription->intervals)->map(function ($i) {
                return sprintf(
                    'Interval: %s - Period: %s - Recommended: %s',
                    $i->interval,
                    $i->period,
                    $i->recommended ? 'Yes' : 'No'
                );
            })->toArray();

            $intervals = implode(' | ', $intervals);
        }

        $data[] = [
            'id' => $product->id,
            'parent' => $product->isVariation() ? $product->parent_id : '',
            'name' => $product->name,
            'sku' => $product->sku,
            'type' => $product->type,
            'price' => $product->subscription->price / 100,
            'fee' => $product->subscription->fee,
            'intervarls' => $intervals,
        ];

        return $data;
    }
}
