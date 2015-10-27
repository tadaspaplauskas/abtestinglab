<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\WebsiteController;

class Website extends Model
{
    const JS_FILENAME = 'tests.js';
    const USERS_PATH = 'users/';
    
    
    protected $table = 'websites';

    protected $fillable = ['user_id', 'enabled', 'url', 'title', 'token', 'deleted_at'];
    
    public function tests()
    {
        return $this->hasMany('App\Models\Test')->where('archived', 0)->orderBy('created_at', 'desc');
    }
    
    public function archivedTests()
    {
        return $this->hasMany('App\Models\Test')->where('archived', 1)->orderBy('created_at', 'desc');
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
        return md5($this->id);
    }
    
    public function jsUrl()
    {
        return url(self::USERS_PATH
                . $this->user->hash() . '/' 
                . $this->hash() . '/'
                . self::JS_FILENAME);
    }

    public function path()
    {
        return public_path(self::USERS_PATH
                . $this->user->hash() . '/' 
                . $this->hash() . '/');
    }
    
    public function jsPath()
    {
        return public_path(self::USERS_PATH
                . $this->user->hash() . '/' 
                . $this->hash() . '/'
                . self::JS_FILENAME);
    }
    
    public function url()
    {
        return url(self::USERS_PATH
                . $this->user->hash() . '/' 
                . $this->hash() . '/');
    }
}
