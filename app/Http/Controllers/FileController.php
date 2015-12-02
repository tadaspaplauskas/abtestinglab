<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\MinifyCompressJS;

class FileController extends Controller
{
    function __construct()
    {
    }

    public static function put($path, $content, $minify = true)
    {
        $dir = dirname($path);
        if (!is_dir($dir))
        {
            mkdir($dir);
        }
        $return = file_put_contents($path, $content, LOCK_EX);

        if ($return)
            return self::minifyJS($path);
        else
            return false;
    }

    public static function minifyJS($path)
    {
        //delete cache anyway
        @unlink($path . '.gz');

        //queue resource intense tasks
        //minify first
        if (filesize($path) > 0)
        {
            new MinifyCompressJS($path);
        }
        return true;
    }

    public static function fileDir($path)
    {
        if (!file_exists($path))
        {
            $path = dirname($path);
            if (!is_dir($path))
                return mkdir($path);
        }
        return true;
    }

}
