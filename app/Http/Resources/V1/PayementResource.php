<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PayementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'total_cost' => $this->total_cost,
            'amount_paid' => $this->amount_paid,
            'is_paid' => $this->is_paid,

        ];
    }
}