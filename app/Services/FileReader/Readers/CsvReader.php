<?php

namespace  App\Services\FileReader\Readers;

use App\Services\FileReader\Contracts\ReaderContract;
use Illuminate\Http\UploadedFile;

class CsvReader implements ReaderContract
{

    public function read(UploadedFile $file)
    {
        $csvData = str_getcsv($file->get(), "\n");
        array_shift($csvData);

        $data = collect($csvData)->map(function ($country) {
            $string = str_getcsv($country, ",");
            return [
                'country' => $string[0],
                'capital' => $string[1]
            ];
        });

        return $data;
    }
}
