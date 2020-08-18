<?php

namespace  App\Services\FileReader\Writers;

use App\Services\FileReader\Contracts\WriterContract;

class CsvWriter implements WriterContract
{
    public function write($data)
    {
        $result = "Country,Capital\n";
        foreach ($data as $country) {
            $result .= $country['country'] . ',' . $country['capital'] . "\n";
        }
        return $result;
    }
}
