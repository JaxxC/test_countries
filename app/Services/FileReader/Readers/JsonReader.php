<?php

namespace  App\Services\FileReader\Readers;

use App\Services\FileReader\Contracts\ReaderContract;
use Illuminate\Http\UploadedFile;

class JsonReader implements ReaderContract
{
    public function read(UploadedFile $file)
    {
        $data = json_decode($file->get(), true);

        return collect($data);
    }
}
