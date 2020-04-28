<?php

namespace Tests\Feature\Models\Video;

use App\Models\Video;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;


abstract class BaseVideoTestCase extends TestCase
{

    use DatabaseMigrations;

    protected $data;
    protected $fileFieldsData = [];

    protected function setUp(): void
    {
        parent::setUp();


        $this->data =  [
            'title'         =>  'Teste_' . uniqid(),
            'description'   => 'description',
            'year_launched' => rand(2001,2020),
            'rating'        => Video::RATING_LIST[array_rand(Video::RATING_LIST)],
            'opened'        => true,
            'duraction'     => rand(40,120)
        ];

        foreach (Video::$fileFields as $field){
            $this->fileFieldsData[$field] = "{$field}.test";
        }
    }

}