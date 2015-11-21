<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\WebsiteController;
use App\User;

class Website extends Model
{
    const JS_FILENAME = 'tests.js';
    
    protected $table = 'websites';

    protected $fillable = ['user_id', 
        'status',
        'url', 
        'title', 
        'token', 
        'deleted_at',
        'published_at',
        'updated_at'];
    
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
        return url(User::USERS_PATH
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
        return url(User::USERS_PATH
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
}
