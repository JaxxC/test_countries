<?php

namespace  App\Services\FileReader;

use App\Services\FileReader\Contracts\ReaderContract;
use Illuminate\Http\UploadedFile;

class FileReader
{

    protected $reader;

    public function __construct(ReaderContract $reader)
    {
        $this->reader = $reader;
    }

    public function readFile(UploadedFile $file)
    {
        return $this->reader->read($file);
    }
}
