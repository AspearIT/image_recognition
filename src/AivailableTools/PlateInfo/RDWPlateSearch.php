<?php

namespace Imreg\AivailableTools\PlateInfo;

class RDWPlateSearch
{
    public function searchInfo(string $plate): CarInfo
    {
        $url = "https://opendata.rdw.nl/resource/m9d7-ebf2.json?kenteken=" . str_replace('-', '', $plate);
        $data = json_decode(file_get_contents($url), true);
        $data = $data[0];
        return new CarInfo(
            $data['kenteken'],
            $data['merk'],
            $data['handelsbenaming'],
            $data['eerste_kleur'],
            $data['datum_eerste_toelating'],
            $data['massa_rijklaar'],
            $data['aantal_zitplaatsen'],
        );
    }
}