<?php

namespace  App\Services\FileReader;

use App\Services\FileReader\Exceptions\TypeNotSupportedException;
use App\Services\FileReader\Readers\{CsvReader, JsonReader, XmlReader};
use App\Services\FileReader\Writers\CsvWriter;
use App\Services\FileReader\Writers\JsonWriter;
use App\Services\FileReader\Writers\XmlWriter;
use Illuminate\Http\UploadedFile;

class FileTypeDetector
{
    const TYPE_JSON = 'json';
    const TYPE_CSV = 'csv';
    const TYPE_XML = 'xml';

    public static function getReader(UploadedFile $file)
    {
        $fileType = $file->getClientOriginalExtension();
        switch ($fileType) {
            case self::TYPE_JSON:
                return new JsonReader();
                break;
            case self::TYPE_CSV:
                return new CsvReader();
                break;
            case self::TYPE_XML:
                return new XmlReader();
                break;
            default:
                throw new TypeNotSupportedException($fileType);
        }
    }

    public static function getWriter($fileType)
    {
        switch ($fileType) {
            case self::TYPE_JSON:
                return new JsonWriter();
                break;
            case self::TYPE_CSV:
                return new CsvWriter();
                break;
            case self::TYPE_XML:
                return new XmlWriter();
                break;
            default:
                throw new TypeNotSupportedException($fileType);
        }
    }
}
