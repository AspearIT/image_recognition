<?php

namespace Imreg\AivailableTools;

use Imreg\AivailableTools\DamageCost\CarType;
use Imreg\AivailableTools\DamageCost\DamageCostCalculator;
use Imreg\AivailableTools\PlateInfo\RDWPlateSearch;
use Imreg\Value\Tool;
use Imreg\Value\ToolCall;

class AvailableTools
{
    public function __construct(
        private readonly RDWPlateSearch $rdwPlateSearch,
        private readonly DamageCostCalculator $damageCostCalculator,
    ) {}

    /**
     * @return Tool[]
     */
    public function getAvailableTools(): array
    {
        $possibleCars = array_map(fn(CarType $carType) => $carType->value, CarType::cases());
        return [
            new Tool(
                'RDWPlateSearch',
                'Show car type information based on the plate number of the car. Call only when explicitly asked for RDW information.',
                ['plate' => 'The plate number of the car'],
            ),
            new Tool(
                'damagePriceEstimation',
                'Show an estimated price of the damage based on the car type and the damage.',
                [
                    'carType' => 'The type of the car. Possible values are ' . implode(', ', $possibleCars),
                    'damage' => 'The damage to the car as a number between 1 (no damage at all) and 10 (total loss)',
                ],
            )
        ];
    }

    public function call(ToolCall $toolCall): string
    {
        switch ($toolCall->name) {
            case 'RDWPlateSearch':
                $carInfo = $this->rdwPlateSearch->searchInfo($toolCall->arguments['plate']);
                if(is_null($carInfo)) {
                    return "Geen kentekeninformatie gevonden";
                }
                $response = [
                    'RDW informatie:',
                    'Kenteken: ' . $carInfo->plate,
                    "Merk: {$carInfo->brand}",
                    "Model: {$carInfo->model}",
                    "Kleur: {$carInfo->color}",
                    "Bouwjaar: {$carInfo->year}",
                    "Catalogusprijs: {$carInfo->cataloguePrice}"
                ];
                return implode("\n", $response);
            case 'damagePriceEstimation':
                $estimation = $this->damageCostCalculator->calculateCost(
                    CarType::from($toolCall->arguments['carType']),
                    (int) $toolCall->arguments['damage'],
                );
                return "De totale schade wordt geschat op €{$estimation->cost}. Dit omdat het een auto van het type {$estimation->carType->value} betreft en omdat de schade ingeschat wordt op {$estimation->damageLevel} op een schaal van 1 tot 10.";
            default:
                throw new \InvalidArgumentException("Unknown tool: {$toolCall->name}");
        }
    }
}