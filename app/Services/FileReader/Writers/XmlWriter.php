<?php

namespace  App\Services\FileReader\Writers;

use App\Services\FileReader\Contracts\WriterContract;
use Spatie\ArrayToXml\ArrayToXml;

class XmlWriter implements WriterContract
{
    public function write($data)
    {
        $data = ['element' => $data];

        return ArrayToXml::convert($data);
    }
}
