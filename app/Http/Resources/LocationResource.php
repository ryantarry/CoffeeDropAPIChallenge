<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'address' => $this->when(isset($this->address_details), function() {
                            return [
                                'ward' => $this->address_details['ward'],
                                'district' => $this->address_details['district'],
                                'county' => $this->address_details['county'],
                                'postcode' => $this->address_details['postcode'],
                                'country' => $this->address_details['country'],
                            ];
                        }),
            'opening_times' => json_decode($this->opening_times, true),
            'closing_times' => json_decode($this->closing_times, true),
        ];
    }
}
