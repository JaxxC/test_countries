<?php

namespace  App\Services\FileReader\Readers;

use App\Services\FileReader\Contracts\ReaderContract;
use Illuminate\Http\UploadedFile;
use SimpleXMLElement;

class XmlReader implements ReaderContract
{
    public function read(UploadedFile $file)
    {
        $xml = new SimpleXMLElement($file->get());
        $data = json_encode($xml);
        $data = json_decode($data, true);

        return collect($data['element']);
    }
}
