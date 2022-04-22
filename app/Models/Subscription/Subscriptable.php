<?php

namespace App\Models\Subscription;

use Carbon\Carbon;

trait Subscriptable {

    /**
     * Undocumented function
     *
     * @return boolean
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
            'price' => (int) $this->getMetaValue('_ks_recurring_price', 0) * 100,
            'fee' => (int) $this->getMetaValue('_ks_subscription_fee', 0) * 100,
            'intervals' => unserialize($this->getMetaValue('_kh_period_intervals', ''))
        ];

        if ($this->isVariation()) {
            $data['use_parent_settings'] = $data['price'] !== 0 && $data['fee'] !== 0;
        } else {
            $data['use_parent_settings'] = false;
        }

        if (! $this->subscription) {
            $this->subscription()->create($data);
        } else {
            $this->subscription()->update($data);
        }

        $this->save();
    }
}
