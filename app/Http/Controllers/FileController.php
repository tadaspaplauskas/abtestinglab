<?php

namespace App\Http\Controllers;

use MatthiasMullie\Minify;

use App\Http\Controllers\Controller;
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

        if ($minify && $return)
        {
            return self::minifyJS($path);
        }
        return $return;
    }

    public static function minifyJS($path)
    {
        //try to delete cache anyway
        @unlink($path . '.gz');

        //queue resource intense tasks
        //minify first
        if (filesize($path) > 0)
        {
            $minifier = new Minify\JS($path);
            $return = $minifier->minify($path);

            //gzip if success
            if ($return)
            {
                $return = $minifier->gzip($path . '.gz', 9);
            }
        }
        return $return;
    }

    public static function fileDir($path)
    {
        if (!file_exists($path))
        {
            $path = dirname($path);
            if (!is_dir($path))
            {
                return mkdir($path);
            }
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
