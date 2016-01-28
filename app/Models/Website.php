<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\WebsiteController;
use App\User;

class Website extends Model
{
    const JS_FILENAME = 'tests.js';

    protected $table = 'websites';

    protected $fillable = [
        'user_id',
        'status',
        'url',
        'title',
        'token',
        'deleted_at',
        'published_at',
        'updated_at',
        'keep_best_variation',
        ];

    protected $dates = ['created_at', 'updated_at', 'published_at', 'deleted_at'];

    public function tests()
    {
        return $this->hasMany('App\Models\Test')->where('status', '!=', 'archived')->orderBy('created_at', 'desc');
    }

    public function enabledTests()
    {
        return $this->hasMany('App\Models\Test')->where('status', 'enabled')->orderBy('created_at', 'desc');
    }

    public function archivedTests()
    {
        return $this->hasMany('App\Models\Test')->where('status', 'archived')->orderBy('created_at', 'desc');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function unpublishedChanges()
    {
        return Test::where('website_id', $this->id)
                ->where('updated_at', '>', $this->published_at)
                ->exists();
    }

    public function hash()
    {
        return md5($this->id); //maybe add salt here
    }

    public function isEnabled()
    {
        return ($this->status === 'enabled') ? true : false;
    }
    public function jsUrl()
    {
        return protocolRelativeUrl(User::USERS_PATH
                . $this->user->hash() . '/'
                . $this->hash() . '/'
                . self::JS_FILENAME);
    }

    public function path()
    {
        return public_path(User::USERS_PATH
                . $this->user->hash() . '/'
                . $this->hash() . '/');
    }

    public function jsPath()
    {
        return public_path(User::USERS_PATH
                . $this->user->hash() . '/'
                . $this->hash() . '/'
                . self::JS_FILENAME);
    }

    public function url()
    {
        return protocolRelativeUrl(User::USERS_PATH
                . $this->user->hash() . '/'
                . $this->hash() . '/');
    }

    public function disableTests()
    {
        foreach ($this->tests as $test)
        {
            $test->disable();
        }
    }

    public function testsCount($status = null)
    {
        $condition = (is_null($status) ? 'true' : 'status = "'. $status .'"');
        $count = Test::where('website_id', $this->id)
                ->whereRaw($condition)
                ->count();

        print_r($count);
        return '';
    }

    public function lastChangesForHumans()
    {
        $test = Test::where('website_id', $this->id)
                ->orderBy('updated_at', 'desc')
                ->take(1)
                ->first();

        if (isset($test->updated_at) && $test->updated_at->timestamp > 0)
            return $test->updated_at->diffForHumans();
        else
            return 'Nothing yet';
    }

    public function jsCode()
    {
        return '<script type="text/javascript" src="' . $this->jsUrl() . '" async></script><script type="text/javascript">var s=document.createElement("style");s.type="text/css";s.appendChild(document.createTextNode("body{visibility:hidden;}"));document.getElementsByTagName("head")[0].appendChild(s);var t=setInterval(function(){if(document.body!=null){document.body.style.visibility="visible";clearInterval(t);}},500);</script>';
    }

    public function jsCodeTextarea()
    {
        return '<textarea style="width:100%;height:6em;" readonly onclick="this.focus();this.select()">'
         . $this->jsCode() .
         '</textarea>';
    }

    public function isScriptOnline()
    {
        $content = file_get_contents($this->url);

        if(stripos($content, $this->jsUrl()) === false)
            return false;
        else
            return true;
    }

    public function getUrlAttribuet($value)
    {
        return (stripos($value, 'http') === 0) ? $value : 'http://' . $value;
    }
}
