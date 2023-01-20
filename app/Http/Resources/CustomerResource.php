<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        return [
            'vehiclemodel'      => $this->vehicle_model,
            'vehiclemake'       => $this->vehicle_make,
            'customername'      => $this->name,
            'id'                => $this->id,
        ];
    }
}
