<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{


    protected function assertStore(
        array $sendData,
        array $testDatabase,
        array $testJsonData = null
    ) {

        $response = $this->json('POST', $this->routeStore(), $sendData);
        if($response->status() !== 201) {
            throw new \Exception("Response status must be 201, given {$response->status()}: \n{$response->content()}");
        }

        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase + [ 'id' => $response->json('id') ]);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);
        return $response;

    }

    protected function assertUpdate(
        array $sendData,
        array $testDatabase,
        array $testJsonData  = null
    ) {

        $response = $this->json('PUT', $this->routeUpdate(), $sendData);
        if($response->status() !== 200) {
            throw new \Exception("Response status must be 200, given {$response->status()}: \n{$response->content()}");
        }

        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testDatabase);
        $this->assertJsonResponseContent($response, $testDatabase, $testJsonData);
        return $response;

    }

    protected function assertJsonResponseContent(
        $response,
        $testDatabase,
        $testJsonData
    ) {
        $testResponse = $testJsonData ?? $testDatabase;
        $response->assertJsonFragment($testResponse);
    }


}