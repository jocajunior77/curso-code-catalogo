<?php

namespace Tests\Feature\Http\Controllers\Api\VideoController;

use App\Models\Video;
use App\Models\Category;
use App\Models\Genre;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;


class BaseVideoControllerTestCase extends TestCase
{

    use DatabaseMigrations;

    protected $video;

    protected function setUp(): void
    {
        parent::setUp();
        $this->video = factory (Video::class)->create();
    }

    protected function sendData($type = null)
    {

        $data =  [
            'title'         =>  'Teste_' . uniqid(),
            'description'   => 'description',
            'year_launched' => rand(2001,2020),
            'rating'        => Video::RATING_LIST[array_rand(Video::RATING_LIST)],
            'opened'        => true,
            'duraction'     => rand(40,120)
        ];

        switch ($type) {
            case '1':
                return ['title' => '','description' => '','year_launched' => '','duraction' => '','categories_id' => '','genres_id' => ''];
                break;
            case '2':
                $data['opened'] = false;
                return $data;
                break;
        }

        return $data;

    }

    protected function appendSendData()
    {

        $category = factory (Category::class)->create();
        $genre    = factory (Genre::class)->create();

        $genre->categories()->attach($category->id);

        return [
            'categories_id' => [ $category->id ],
            'genres_id' => [ $genre->id ],
        ];
    }
}
