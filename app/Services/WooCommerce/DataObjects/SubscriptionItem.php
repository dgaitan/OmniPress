<?php

namespace App\Services\WooCommerce\DataObjects;

use App\Services\Contracts\DataObjectContract;
use App\Services\DataObjects\BaseObject;
use App\Models\WooCommerce\OrderLine as WooOrderLine;
use App\Models\WooCommerce\Product;
use App\Models\Subscription\KindhumanSubscriptionItem;

class SubscriptionItem extends BaseObject implements DataObjectContract
{
    /**
     * Order line schema
     *
     * @return void
     */
    protected function schema(): void {
        $this->integer('id', 0); // this is the product id
        $this->integer('variation_id', 0);
        $this->integer('regular_price', 0);
        $this->integer('subscription_price', 0);
        $this->integer('total', 0);
        $this->integer('fee', 0);
        $this->integer('qty', 1);
        $this->integer('subscription_id', 0);
    }

    /**
     * Sync OrderLine
     *
     * @return WooOrderLine
     */
    public function sync(): KindhumanSubscriptionItem {
        $productId = $this->id === 0 ? $this->variation_id : $this->id;
        $subscriptionItem = KindhumanSubscriptionItem::firstOrNew([
            'product_id' => $productId
        ]);

        $data = $this->toArray('product_id');
        $data['product_id'] = $productId;
        $data['quantity'] = $data['qty'];
        $data['price'] = $data['subscription_price'];
        
        $subscriptionItem->fill($data);
        $subscriptionItem->save();

        return $subscriptionItem;
    }
}
