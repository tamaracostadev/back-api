<?php

namespace Tests\Unit;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
//use PHPUnit\Framework\TestCase;

class EnspointStoreTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testNotSendFileFails()
    {
        $this->json('POST', 'api/v1/store', ['Accept' => 'multipart/form-data'])
            ->assertStatus(401)
            ->assertExactJson([
                    'errors'=>true, 
                    'message'=> [
                        "file" => [
                            "The file field is required."
                        ]
                    ]  
            ]);
    }
    

}
