<?php

namespace Imreg\AivailableTools\PlateInfo;

class RDWPlateSearch
{
    public function searchInfo(string $plate): ?CarInfo
    {
        if (!is_null($plate)) {
            $url = "https://opendata.rdw.nl/resource/m9d7-ebf2.json?kenteken=" . str_replace('-', '', $plate);
            $data = json_decode(file_get_contents($url), true);
            if(count($data) == 1) {
                $data = $data[0];
                return new CarInfo(
                    $data['kenteken'],
                    $data['merk'],
                    $data['handelsbenaming'],
                    $data['eerste_kleur'],
                    substr($data['datum_eerste_toelating'], 0, 4),
                    $data['massa_rijklaar'],
                    $data['aantal_zitplaatsen'],
                    isset($data['catalogusprijs']) ? $data['catalogusprijs'] : 'Onbekend',
                );
            }
        }
        return null;
    }
}