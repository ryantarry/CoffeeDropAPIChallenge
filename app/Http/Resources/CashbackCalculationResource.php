<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CashbackCalculationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ristretto' => $this->ristretto,
            'espresso' => $this->espresso,
            'lungo' => $this->lungo,
            'cashback' => '£' . number_format($this->cashback / 100, 2),
        ];
    }
}
