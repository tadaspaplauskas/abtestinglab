<?php

namespace App\Http\Controllers\API;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\TestController;
use App\Models\Conversion;

class ConversionController extends ApiController
{
    function __construct()
    {
    }

    public function saveConversion(Request $request)
    {
        //check host, if it comes from the right website
        $testID = (int) $request->get('test_id');
        $variation = $request->get('variation');
        $visitorID = (int) $request->get('visitor_id');
        
        if ($testID > 0 && ($variation === 'a' OR $variation === 'b'))
        {
            $conversion = Conversion::firstOrNew([
                'test_id' => $testID,
                'visitor_id' => $visitorID,
                'variation' => $variation]);

            $conversion->count++;
            $conversion->save();
            
            $test = $conversion->test;
                
            if ($conversion->count === 1)
            {
                if ($variation === 'a')
                    $test->original_conversion_count++;
                else if ($variation === 'b')
                    $test->variation_conversion_count++;
                
                $test->save();
            }
            //goal is reached
            if ($test->totalReach() >= $test->goal)
            {
                $test->disable();
                $testController = new TestController();
                $testController->refreshTestsJS($test->website);
                
                Event::fire(new TestCompleted($test));
            }
            return $this->respondSuccess();
        }        
        return $this->respondError();
    }
}
