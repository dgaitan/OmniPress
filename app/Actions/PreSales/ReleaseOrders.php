<?php

namespace App\Actions\PreSales;

use App\Models\PreSales\PreOrder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

class ReleaseOrders {
    use AsAction;

    public function handle() {
        $query = PreOrder::whereReleased(false)
            ->whereReleaseDate(Carbon::today());

        $offset = 0;
        $perPage = 50;
        $page = 1;
        $items = $query->skip($offset)->take($perPage)->get();

        while ($items->isNotEmpty()) {
            $page++;

            Http::withToken(env('PRE_SALE_TOKEN', 'token'))
                ->withHeaders(['Content-Type', 'application/json'])
                ->post(env('PRE_SALE_ENDPOINT', ''), [
                    'orders' => $items->pluck('woo_order_id')->toArray(),
                ]);

            $items->map(function ($order) {
                $order->update([
                    'released' => true,
                    'status' => 'processing'
                ]);
            });
            $offset = $offset + $perPage;
            $items = $query->skip($offset)->take($perPage)->get();
        }
    }
}
