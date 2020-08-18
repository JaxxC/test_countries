<?php

namespace  App\Services\FileReader\Writers;

use App\Services\FileReader\Contracts\WriterContract;

class JsonWriter implements WriterContract
{
    public function write($data)
    {
        return json_encode($data);
    }
}
