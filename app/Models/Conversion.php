<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversion extends Model
{
    protected $table = 'conversions';

    protected $fillable = [
        'test_id',
        'visitor_id',
        'variation'];

    public function test()
    {
        return $this->belongsTo('App\Models\Test');
    }
    
   
}