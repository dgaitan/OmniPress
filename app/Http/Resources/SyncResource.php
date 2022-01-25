<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SyncResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at->format('F j, Y @ H:i:s'),
            'content_type' => $this->content_type,
            'name' => $this->name,
            'description' => $this->description,
            'status' => $this->status,
            'batch_id' => $this->batch_id,
            'user' => array(
                'id' => $this->user->id,
                'name' => $this->user->name
            )
        ];
    }
}
