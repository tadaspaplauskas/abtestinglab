<?php

namespace App\Models;

use Auth;
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
    
    public function scopeEnabled($query)
    {
        return $query->where('status', 'enabled');
    }
    
    public function scopeDisabled($query)
    {
        return $query->where('status', 'disabled');
    }
    
    public function scopeMy($query)
    {
        $user = Auth::user();
        if (Auth::check())
        {
            return $query
                ->whereRaw('website_id IN (SELECT website_id FROM websites WHERE user_id='. $user->id .')');
        }
        else
        {
            return $query->whereRaw('true = false');
        }
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
            $calc = round(($calcVar / $calcOrig - 1) * 100, 2);        
            return $calc;
        }
    }
    
    public function convDiff()
    {
        return $this->variationConv() - $this->originalConv();
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
    
    public function calculateConfidence($percentage = true)
    {
        $controlConv = $this->original_conversion_count;
        $controlVisitors = $this->original_pageviews;
        
        $variationConv = $this->variation_conversion_count;
        $variationVisitors = $this->variation_pageviews;
        
        if ($controlConv === 0 || $controlVisitors === 0
                || $variationConv === 0 || $variationVisitors === 0)
            return 0;
        
        $controlP = $controlConv / $controlVisitors;
        $variationP = $variationConv / $variationVisitors;
        
        $controlSE = sqrt($controlP * (1 - $controlP) / $controlVisitors);
        $variationSE = sqrt($variationP * (1 - $variationP) / $variationVisitors);
        
        $zScore = ($controlP - $variationP) /
                sqrt(pow($controlSE, 2) + pow($variationSE, 2));
        
        //$pValue = stats_cdf_normal($zScore, 0, 1, 1);
        $pValue = self::cumnormdist($zScore);
        
        if ($pValue < 0.5)
            $confidence = (1 - $pValue);
        else
            $confidence = $pValue;
        
        if ($percentage)
        {
            $confidence = round($confidence * 100);
        }
        
        return $confidence;
    }
    
    public static function cumNormDist($x)
    {
      $b1 =  0.319381530;
      $b2 = -0.356563782;
      $b3 =  1.781477937;
      $b4 = -1.821255978;
      $b5 =  1.330274429;
      $p  =  0.2316419;
      $c  =  0.39894228;

      if($x >= 0.0) {
          $t = 1.0 / ( 1.0 + $p * $x );
          return (1.0 - $c * exp( -$x * $x / 2.0 ) * $t *
          ( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
      }
      else {
          $t = 1.0 / ( 1.0 - $p * $x );
          return ( $c * exp( -$x * $x / 2.0 ) * $t *
          ( $t *( $t * ( $t * ( $t * $b5 + $b4 ) + $b3 ) + $b2 ) + $b1 ));
        }
    }
}