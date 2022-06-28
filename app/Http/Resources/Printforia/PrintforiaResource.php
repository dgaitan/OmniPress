<?php

namespace App\Http\Resources\Printforia;

use Illuminate\Http\Resources\Json\JsonResource;

class PrintforiaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'id' => $this->id,
            'order' => [
                'id' => $this->order->id,
                'order_id' => $this->order->order_id,
            ],
            'created_at' => $this->order->created_at->diffForHumans(),
            'status' => $this->status,
            'printforia_order_id' => $this->printforia_order_id,
            'shipping_method' => $this->shipping_method,
            'customer_reference' => $this->customer_reference,
            'ship_to_address' => $this->ship_to_address,
            'return_to_address' => $this->return_to_address,
            'ship_to_address_formatted' => $this->shippingAddress(),
            'return_to_address_formatted' => $this->returnAddress(),
            'permalink' => $this->getPermalink(),
            'items' => [],
            'notes' => [],
        ];

        if ($this->items->isNotEmpty()) {
            $data['items'] = $this->items->map(function ($item) {
                return [
                    'item_id' => $item->printforia_item_id,
                    'customer_item_reference' => $item->customer_item_reference,
                    'sku' => $item->printforia_sku,
                    'kindhumans_sku' => $item->kindhumans_sku,
                    'quantity' => $item->quantity,
                    'description' => $item->description,
                    'prints' => $item->prints,
                    'product' => [
                        'id' => $item->product->id,
                        'product_id' => $item->product->product_id,
                        'name' => $item->product->name,
                        'sku' => $item->product->sku,
                        'image' => $item->product->featuredImage(),
                    ],
                ];
            });
        }

        if ($this->notes->isNotEmpty()) {
            $data['notes'] = $this->notes->map(function ($note) {
                return [
                    'id' => $note->id,
                    'title' => $note->title,
                    'body' => $note->body,
                    'order_status_code' => $note->order_status_code,
                    'note_date' => $note->note_date->diffForHumans(),
                ];
            });
        }

        return $data;
    }
}
