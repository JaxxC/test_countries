<?php

namespace  App\Services\FileReader;

use App\Services\FileReader\Contracts\WriterContract;

class FileWriter
{

    protected $writer;

    public function __construct(WriterContract $writer)
    {
        $this->writer = $writer;
    }

    public function writeFile($data)
    {
        return $this->writer->write($data);
    }
}
