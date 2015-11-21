<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $table = 'visitors';

    protected $fillable = [
        'hash',
        'ip',
        'website_id',
        'user_agent',
        'tests',
        ];

    public function conversions()
    {
        return $this->hasMany('App\Models\Conversion');
    }
    
   
}