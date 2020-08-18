<?php

namespace  App\Services\FileReader\Contracts;

use Illuminate\Http\UploadedFile;

interface ReaderContract
{
    public function read(UploadedFile $file);
}
