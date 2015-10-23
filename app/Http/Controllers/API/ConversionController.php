<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;

use App\Models\Conversion;

class ConversionController extends ApiController
{
    private $user;
    
    function __construct()
    {
        //TODO authentication with some kind of token
        $this->user = new \stdClass();
        $this->user->id = 1;
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
        }
        return '';
    }
}
