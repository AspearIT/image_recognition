<?php

namespace Imreg\AivailableTools\PlateInfo;

readonly class CarInfo
{
    public function __construct(
        public string $plate,
        public string $brand,
        public string $model,
        public string $color,
        public string $year,
        public string $weight,
        public string $numberOfSeats,
    ) {}
}