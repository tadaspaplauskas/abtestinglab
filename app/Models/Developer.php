<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Developer extends Model
{
    protected $table = 'developers';

    protected $fillable = [
        'name',
        'user_id',
        'website_id',
        'email',
        ];

    public function website()
    {
        return $this->belongsTo('App\Models\Website');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


}