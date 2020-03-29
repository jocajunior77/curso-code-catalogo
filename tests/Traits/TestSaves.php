<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;

trait TestSaves
{


    protected function assertStore(
        $sendData,
        $testData,
        array $ruleParams = []
    ) {

        $response = $this->json('POST', $this->routeStore(), $sendData);
        if($response->status() !== 201) {
            throw new \Exception("Response status must be 201, given {$response->status()}: \n{$response->content()}");
        }

        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testData + [ 'id' => $response->json('id') ]);

    }

    protected function assertUpdate(
        $sendData,
        $testData,
        array $ruleParams = []
    ) {

        $response = $this->json('PUT', $this->routeUpdate(), $sendData);
        if($response->status() !== 200) {
            throw new \Exception("Response status must be 200, given {$response->status()}: \n{$response->content()}");
        }

        $model = $this->model();
        $table = (new $model)->getTable();
        $this->assertDatabaseHas($table, $testData);

    }


}