<?php

namespace App\Http\Resources;

use App\Http\Resources\CustomerResource;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentsResource extends JsonResource
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
            'day'               => $this->day,
            'month'             => $this->month,
            'year'              => $this->year,
            'time'              => $this->time,
            'customer'          => $this->customerDetails->name,
            'vehiclemake'       => $this->customerDetails->vehicle_make,
            'vehiclemodel'      => $this->customerDetails->vehicle_model,
        ];
    }
}
