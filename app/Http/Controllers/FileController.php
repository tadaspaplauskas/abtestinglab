<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\MinifyCompressJS;
use Image;

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

        if ($minify)
        {
            if ($return)
                return self::minifyJS($path);
            else
                return false;
        }
        return $return;
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
    
    public static function makeImage($base64, $path)
    {
        $img = Image::make($base64);
        // not for now, but probably should in the future - scaling
        // $img->resize(self::ONE_SIZE_WIDTH, self::ONE_SIZE_HEIGHT, function ($constraint){$constraint->aspectRatio();});
        $umask = umask(0);
        //just to be sure that directory exists - shit happens
        self::fileDir($path);                        
        $img->save($path);
        chmod($path, 0664);
        umask($umask);

        return $img;
    }

}
