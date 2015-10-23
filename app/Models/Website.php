<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\WebsiteController;

class Website extends Model
{
    protected $table = 'websites';

    protected $fillable = ['user_id', 'enabled', 'url', 'title', 'token', 'deleted_at'];
    
    public function tests()
    {
        return $this->hasMany('App\Models\Test')->orderBy('created_at', 'desc');
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
    

}
