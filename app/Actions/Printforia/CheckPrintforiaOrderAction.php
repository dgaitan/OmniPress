<?php

namespace App\Actions\Printforia;

use App\Actions\WooCommerce\Orders\UpdateOrderAction;
use App\Models\Printforia\PrintforiaOrder;
use Lorisleiva\Actions\Concerns\AsAction;

class CheckPrintforiaOrderAction
{
    use AsAction;

    public function handle(PrintforiaOrder $printforiaOrder)
    {
        $printforiaOrder = CreateOrUpdatePrintforiaOrderAction::run(
            $printforiaOrder->order, $printforiaOrder->printforia_order_id
        );

        if (in_array($printforiaOrder->status, ['shipped', 'completed'])) {
            UpdateOrderAction::run($printforiaOrder->order->order_id, [
                'status' => 'completed'
            ], true);
        }
    }
}
