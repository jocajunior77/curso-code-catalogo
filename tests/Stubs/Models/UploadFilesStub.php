<?php

namespace Tests\Stubs\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\UploadFiles;

class UploadFilesStub extends Model
{

    use UploadFiles;

    public function uploadDir()
    {
        return '1';
    }

}