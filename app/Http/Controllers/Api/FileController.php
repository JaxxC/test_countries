<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\DownloadFileRequest;
use Illuminate\Support\Str;
use App\Http\Requests\UploadFileRequest;
use App\Http\Resources\CountryResource;
use App\Models\FormFile;
use App\Services\FileReader\FileReader;
use App\Services\FileReader\FileTypeDetector;
use App\Services\FileReader\FileWriter;

class FileController extends Controller
{

    /**
     * Upload new file for parsing.
     */
    public function upload(UploadFileRequest $request)
    {
        $file = $request->file('file');
        $reader = FileTypeDetector::getReader($file);
        $fileReader = new FileReader($reader);
        $countries = $fileReader->readFile($file);

        return CountryResource::collection($countries);
    }

    /**
     * Download file
     */
    public function download(DownloadFileRequest $request)
    {
        return response()->streamDownload(function () use ($request) {
            $writer = FileTypeDetector::getWriter($request->type);
            $fileWriter = new FileWriter($writer);
            echo $fileWriter->writeFile($request->countries);
        }, 'countries.' . $request->type);
    }
}
