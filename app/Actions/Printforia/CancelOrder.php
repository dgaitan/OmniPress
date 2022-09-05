<?php

namespace App\Actions\Printforia;

use App\Models\Printforia\PrintforiaOrder;
use App\Services\Printforia\PrintforiaApiClient;
use Illuminate\Support\Facades\Log;
use Lorisleiva\Actions\Concerns\AsAction;

class CancelOrder
{
    use AsAction;

    public function handle(PrintforiaOrder $order): PrintforiaOrder
    {
        $response = (new PrintforiaApiClient)
            ->cancellOrder($order->printforia_order_id);

        if ($response->failed()) {
            Log::warning(
                sprintf(
                    'We are not able to cancell Printforia Order %s because %s',
                    $order->printforia_order_id,
                    $response->body()
                )
            );

            return $order;
        }

        $order->status = 'canceled';
        $order->save();

        CreateOrUpdatePrintforiaOrderAction::dispatch(
            $order->order, $order->printforia_order_id
        )->delay(now()->addSeconds(5));

        return $order;
    }
}
