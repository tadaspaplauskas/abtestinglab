<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';

    protected $fillable = ['website_id', 'enabled', 'title', 'test_element', 'element_type', 'test_variation',
        'conversion_type' /* click */, 'orginal_conversion_count', 'variation_conversion_count', 'original_pageviews', 
        'variation_pageviews', 'adaptive', 'goal_type' /* conversions, views */, 'goal', 'start', 'end'];

    function website()
    {
        $this->belongsTo('App\Models\Website');
    }
}