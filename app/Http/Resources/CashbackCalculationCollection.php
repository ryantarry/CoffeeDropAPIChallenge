<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CashbackCalculationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(function ($calculation) {
            return [
                'Ristretto' => (int) $calculation->ristretto,
                'Espresso' => (int) $calculation->espresso,
                'Lungo' => (int) $calculation->lungo,
                'cashback' => '£' . number_format($calculation->cashback / 100, 2),
            ];
        })->toArray();
    }
}
