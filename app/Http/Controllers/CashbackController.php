<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateCashbackRequest;
use App\Http\Resources\CashbackCalculationCollection;
use App\Http\Resources\CashbackCalculationResource;
use App\Models\CashbackCalculation;

class CashbackController extends Controller
{
    public function calculateCashback(CalculateCashbackRequest $request)
    {
        $validatedData = $request->validated();

        $ristretto = $validatedData['Ristretto'];
        $espresso = $validatedData['Espresso'];
        $lungo = $validatedData['Lungo'];

        $cashback = $this->calculateCashbackForType($ristretto, [2, 3, 5]) +
                    $this->calculateCashbackForType($espresso, [4, 6, 10]) +
                    $this->calculateCashbackForType($lungo, [6, 9, 15]);

        $calculation = CashbackCalculation::create([
            'ristretto' => $ristretto,
            'espresso' => $espresso,
            'lungo' => $lungo,
            'cashback' => $cashback,
        ]);

        return new CashbackCalculationResource($calculation);
    }

    private function calculateCashbackForType($quantity, $rates)
    {
        if ($quantity <= 50) {
            return $quantity * $rates[0];
        } elseif ($quantity <= 500) {
            return 50 * $rates[0] + ($quantity - 50) * $rates[1];
        } else {
            return 50 * $rates[0] + 450 * $rates[1] + ($quantity - 500) * $rates[2];
        }
    }

    public function calculationHistory()
    {   
        $lastFiveCalculations = CashbackCalculation::orderBy('created_at', 'desc')
            ->take(5)
            ->get();
    
        return new CashbackCalculationCollection($lastFiveCalculations);
    }
}
