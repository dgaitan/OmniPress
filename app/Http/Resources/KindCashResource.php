<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KindCashResource extends JsonResource
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
            'points' => is_null($this->points) ? 0 : $this->points,
            'last_earned' => is_null($this->last_earned) ? 0 : $this->last_earned
        ];

        if ($this->logs) {
            $data['logs'] = $this->logs()->get()->map(function($log) {
                return [
                    'event' => $log->event,
                    'date' => $log->date->toDateString(),
                    'order_id' => $log->order_id,
                    'points' => $log->points,
                    'description' => $log->description
                ];
            });
        }

        return $data;
    }
}
