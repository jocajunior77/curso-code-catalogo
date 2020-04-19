<?php

namespace Tests\Traits;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Arr;
use \Storage;

trait TestStorages
{

    protected function deleteAllFiles($dir = null)
    {
        $dirs = $dir ?  Arr::wrap($dir) : Storage::directories();

        foreach ($dirs as $dir) {
            $files = Storage::files($dir);
            Storage::delete($files);
            Storage::deleteDirectory($dir);
        }

    }
}