<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $table = 'websites';

    protected $fillable = ['user_id', 'enabled', 'url', 'title', 'deleted_at'];
    
    public function tests() {
        $this->hasMany('App\Models\Test');
    }
    
}
