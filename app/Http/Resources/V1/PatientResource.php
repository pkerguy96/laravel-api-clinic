<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
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
            'nom' => $this->nom,
            'prenom' => $this->prenom,
            'cin' => $this->cin,
            'date' => $this->date,
            'address' => $this->address,
            'sex' => $this->sex,
            'phoneNumber' => $this->phone_number,
            'mutuelle' => $this->mutuelle,

            // Add other attributes you want to include in the resource
        ];
    }
}
