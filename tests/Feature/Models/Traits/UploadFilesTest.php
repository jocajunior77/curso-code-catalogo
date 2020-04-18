<?php

namespace Tests\Feature\Models\Traits;

use Tests\TestCase;
use Tests\Stubs\Models\UploadFilesStub;

class UploadFilesTest extends TestCase
{
    private $obj;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = new UploadFilesStub();

        UploadFilesStub::dropTable();
        UploadFilesStub::makeTable();
    }

    public function testMakeOldFieldsOnSaving()
    {

        $this->obj->fill([
            'name' => 'teste',
            'file1' => 'teste1.mp4',
            'file2' => 'teste2.mp4'
        ]);
        $this->obj->save();

        $this->assertCount(0, $this->obj->oldFiles);

        $this->obj->update([
            'name' => 'teste_name',
            'file2' => 'teste3.mp4'
        ]);

        $this->assertEqualsCanonicalizing(['teste2.mp4'], $this->obj->oldFiles);
    }


    public function testeMakeOldFilesNullOnSaving()
    {

        $this->obj->fill([
            'name' => 'teste'
        ]);
        $this->obj->save();

        $this->obj->update([
            'name' => 'teste_name',
            'file2' => 'teste3.mp4'
        ]);

        $this->assertEqualsCanonicalizing([], $this->obj->oldFiles);
    }

}