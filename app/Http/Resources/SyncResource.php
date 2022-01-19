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
            'info' => $this->info,
            'created_at' => $this->created_at->format('F j, Y @ H:m:s'),
            'user' => array(
                'id' => $this->user->id,
                'name' => $this->user->name
            )
        ];
    }
}
