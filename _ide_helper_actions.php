<?php

namespace App\Actions\Donations;

/**
 * @method static \Lorisleiva\Actions\Decorators\JobDecorator|\Lorisleiva\Actions\Decorators\UniqueJobDecorator makeJob(int|string $orderId)
 * @method static \Lorisleiva\Actions\Decorators\UniqueJobDecorator makeUniqueJob(int|string $orderId)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(int|string $orderId)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchIf(bool $boolean, int|string $orderId)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchUnless(bool $boolean, int|string $orderId)
 * @method static dispatchSync(int|string $orderId)
 * @method static dispatchNow(int|string $orderId)
 * @method static dispatchAfterResponse(int|string $orderId)
 * @method static mixed run(int|string $orderId)
 */
class AssignOrderDonationAction
{
}
/**
 * @method static \Lorisleiva\Actions\Decorators\JobDecorator|\Lorisleiva\Actions\Decorators\UniqueJobDecorator makeJob(int|string $orderId)
 * @method static \Lorisleiva\Actions\Decorators\UniqueJobDecorator makeUniqueJob(int|string $orderId)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(int|string $orderId)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchIf(bool $boolean, int|string $orderId)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchUnless(bool $boolean, int|string $orderId)
 * @method static dispatchSync(int|string $orderId)
 * @method static dispatchNow(int|string $orderId)
 * @method static dispatchAfterResponse(int|string $orderId)
 * @method static void run(int|string $orderId)
 */
class AssignOrderDonationToCustomerAction
{
}
namespace App\Actions\Printforia;

/**
 * @method static \Lorisleiva\Actions\Decorators\JobDecorator|\Lorisleiva\Actions\Decorators\UniqueJobDecorator makeJob(string $printforiaOrderId)
 * @method static \Lorisleiva\Actions\Decorators\UniqueJobDecorator makeUniqueJob(string $printforiaOrderId)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(string $printforiaOrderId)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchIf(bool $boolean, string $printforiaOrderId)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchUnless(bool $boolean, string $printforiaOrderId)
 * @method static dispatchSync(string $printforiaOrderId)
 * @method static dispatchNow(string $printforiaOrderId)
 * @method static dispatchAfterResponse(string $printforiaOrderId)
 * @method static mixed run(string $printforiaOrderId)
 */
class CreateOrUpdatePrintforiaOrderAction
{
}
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
class MaybeCreatePrintforiaOrderAction
{
}
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
class SyncCustomerIfExistsAction
{
}
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