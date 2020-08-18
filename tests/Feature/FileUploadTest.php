<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Response;

class FileUploadTest extends TestCase
{
    protected $responseResult = [
        "data" => [
            ["country" => "Ukraine", "capital" => "Kyiv"],
            ["country" => "Germany", "capital" => "Berlin"],
            ["country" => "USA", "capital" => "Washington"]
        ]
    ];
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_upload_json_works()
    {
        $file = __DIR__ . '/../assets/countries.json';
        $name = 'countries.json';
        $path = sys_get_temp_dir() . '/' . $name;

        copy($file, $path);

        $file = new UploadedFile($path, $name, 'application/json', null, true);
        $this->call('POST', '/api/file/upload', [], [], ['file' => $file], ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson($this->responseResult);
    }

    public function test_upload_csv_works()
    {
        $file = __DIR__ . '/../assets/countries.csv';
        $name = 'countries.csv';
        $path = sys_get_temp_dir() . '/' . $name;

        copy($file, $path);

        $file = new UploadedFile($path, $name, 'application/json', null, true);
        $this->call('POST', '/api/file/upload', [], [], ['file' => $file], ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson($this->responseResult);
    }

    public function test_upload_xml_works()
    {
        $file = __DIR__ . '/../assets/countries.xml';
        $name = 'countries.xml';
        $path = sys_get_temp_dir() . '/' . $name;

        copy($file, $path);

        $file = new UploadedFile($path, $name, 'application/json', null, true);
        $this->call('POST', '/api/file/upload', [], [], ['file' => $file], ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK)
            ->assertExactJson($this->responseResult);
    }

    public function test_upload_wrong_format()
    {
        $file = __DIR__ . '/../assets/countries.txt';
        $name = 'countries.txt';
        $path = sys_get_temp_dir() . '/' . $name;

        copy($file, $path);

        $file = new UploadedFile($path, $name, 'application/json', null, true);
        $this->call('POST', '/api/file/upload', [], [], ['file' => $file], ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_BAD_REQUEST)
            ->assertExactJson(['error' => "Type \"txt\" not supported"]);
    }
}
