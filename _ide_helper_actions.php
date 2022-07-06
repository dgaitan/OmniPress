<?php

namespace App\Actions\WooCommerce\Orders;

/**
 * @method static \Lorisleiva\Actions\Decorators\JobDecorator|\Lorisleiva\Actions\Decorators\UniqueJobDecorator makeJob(\App\Models\WooCommerce\Order $order)
 * @method static \Lorisleiva\Actions\Decorators\UniqueJobDecorator makeUniqueJob(\App\Models\WooCommerce\Order $order)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(\App\Models\WooCommerce\Order $order)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchIf(bool $boolean, \App\Models\WooCommerce\Order $order)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchUnless(bool $boolean, \App\Models\WooCommerce\Order $order)
 * @method static dispatchSync(\App\Models\WooCommerce\Order $order)
 * @method static dispatchNow(\App\Models\WooCommerce\Order $order)
 * @method static dispatchAfterResponse(\App\Models\WooCommerce\Order $order)
 * @method static mixed run(\App\Models\WooCommerce\Order $order)
 */
class SyncOrderLineItemProductsAction
{
}
namespace Lorisleiva\Actions\Concerns;

/**
 * @method void asController()
 */
trait AsController
{
}
/**
 * @method void asListener()
 */
trait AsListener
{
}
/**
 * @method void asJob()
 */
trait AsJob
{
}
/**
 * @method void asCommand(\Illuminate\Console\Command $command)
 */
trait AsCommand
{
}