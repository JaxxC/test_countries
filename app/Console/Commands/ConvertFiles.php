<?php

namespace App\Console\Commands;

use App\Services\FileReader\FileReader;
use App\Services\FileReader\FileTypeDetector;
use App\Services\FileReader\FileWriter;
use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ConvertFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'convert:countries {--input-file=} {--output-file=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $fileInputName = $this->option('input-file');
        $file = new UploadedFile(base_path($fileInputName), $fileInputName, 'application/octet-stream', null, true);
        $reader = FileTypeDetector::getReader($file);
        $fileReader = new FileReader($reader);
        $countries = $fileReader->readFile($file)->toArray();

        $fileOutputName = $this->option('output-file');
        $fileOutputExtension = explode('.', $fileOutputName)[1];
        $writer = FileTypeDetector::getWriter($fileOutputExtension);
        $fileWriter = new FileWriter($writer);
        
        File::put(base_path($fileOutputName), $fileWriter->writeFile($countries));
    }
}
