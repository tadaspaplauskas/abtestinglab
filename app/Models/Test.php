<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';

    protected $fillable = [
        'website_id',
        'status',
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
    
    public function totalReach()
    {
        return $this->original_pageviews + $this->variation_pageviews;
    }
    
    public function disable()
    {
        $this->status = 'disabled';
        return $this->save();
    }
    
    public function enable()
    {
        $this->status = 'enabled';
        return $this->save();
    }
    
    public function archive()
    {
        $this->status = 'archived';
        return $this->save();
    }
    
    public function isDisabled()
    {
        return ($this->status === 'disabled' ? true : false);
    }
    
    public function isEnabled()
    {
        return ($this->status === 'enabled' ? true : false);
    }
    
    public function isArchived()
    {
        return ($this->status === 'archived' ? true : false);
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
        if ($this->variation_pageviews == 0)
            return 0;
        
        $calc = round($this->variation_conversion_count / $this->variation_pageviews * 100, 2);
        return $calc;
    }
    
    public function convChange()
    {
        if ($this->original_pageviews === 0 || $this->variation_pageviews === 0)
            return 0;
        
        $calcOrig = $this->original_conversion_count / $this->original_pageviews * 100;
        
        $calcVar = $this->variation_conversion_count / $this->variation_pageviews * 100;
        
        if ($calcOrig === 0)
        {
            return 0;
        }
        else 
        {
            $calc = round($calcVar / $calcOrig, 2);        
            return $calc;
        }
    }
    
    public function imagePath()
    {
        return $this->website->path() . 'images/' . $this->id . '.jpg';        
    }
    
    public function imageUrl()
    {
        return asset($this->website->url() . '/images/' . $this->id . '.jpg');
    }
    
    public function nullifyStatistics()
    {
        $this->original_conversion_count = 0;
        $this->variation_conversion_count = 0;
        $this->original_pageviews = 0;
        $this->variation_pageviews = 0;
        return $this->save();
    }
}