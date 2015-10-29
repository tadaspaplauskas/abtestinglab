<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';

    protected $fillable = [
        'website_id',
        'enabled',
        'title',
        'test_element',
        'element_type',
        'test_variation',
        'conversion_type',
        /* click,*/
        'original_conversion_count',
        'variation_conversion_count',
        'original_pageviews', 
        'variation_pageviews',
        'adaptive',
        'goal_type',
        /* conversions, views, */
        'goal',
        'start',
        'end',
        'attributes',
        ];

    public function website()
    {
        return $this->belongsTo('App\Models\Website');
    }
    
    public function originalConv()
    {
        if ($this->original_pageviews == 0)
            return 0;
        
        $calc = round($this->original_conversion_count / $this->original_pageviews * 100, 2);
        return $calc;
        
    }
    
    public function variationConv()
    {
        if ($this->original_pageviews == 0)
            return 0;
        
        $calc = round($this->variation_conversion_count / $this->variation_pageviews * 100, 2);
        return $calc;
    }
    
    public function convChange()
    {
        if ($this->original_pageviews == 0 || $this->variation_pageviews == 0)
            return 0;
        
        $calcOrig = $this->original_conversion_count / $this->original_pageviews * 100;
        
        $calcVar = $this->variation_conversion_count / $this->variation_pageviews * 100;
        
        $calc = round($calcVar / $calcOrig, 2);
        
        return $calc;
    }
    
    public function imagePath()
    {
        return $this->website->path() . 'images/' . $this->id . '.jpg';        
    }
    
    public function imageUrl()
    {
        return asset($this->website->url() . '/images/' . $this->id . '.jpg');
    }
}