<?php

namespace Imreg\AivailableTools\DamageCost;

readonly class Estimation
{
    public function __construct(
        public CarType $carType,
        public int $damageLevel,
        public float $cost,
    ) {}
}