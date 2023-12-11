<?php

namespace App\Http\Resources\V1;

use App\Http\Resources\V1\PayementResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\V1\PatientResource;
use Illuminate\Support\Carbon;

class OperationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray($request)
    {
        return $this->collection->map(function ($operation) {
            return [
                'id' => $operation->id,
                'patient_id' => $operation->patient_id,
                'tooth_id' => $operation->tooth_id,
                'operation_type' => $operation->operation_type,
                'note' => $operation->note,
                'date' => Carbon::parse($operation->created_at)->toDateString(),
                'payments' => PayementResource::collection($operation->payments),
                'patient' => [
                    'nom' => $operation->patient->nom,
                    'prenom' => $operation->patient->prenom,
                ],

            ];
        });
    }
}
