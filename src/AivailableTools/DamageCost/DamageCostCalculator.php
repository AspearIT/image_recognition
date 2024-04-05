<?php

namespace Imreg\AivailableTools\DamageCost;

class DamageCostCalculator
{
    public function calculateCost(CarType $carType, int $levelOfDamage): Estimation
    {
        $costs = $levelOfDamage * match($carType) {
            CarType::DACIA => 124.0,
            CarType::OTHER => 264.0,
            CarType::RENAULT => 30_000.0,
            CarType::PEUGEOT => 25_000.0,
            CarType::MERCEDES => 10_000.0,
            CarType::CITROEN => 7_000.0,
            CarType::BMW => 10_000.0,
        };

        return new Estimation($carType, $levelOfDamage, $costs);
    }
}