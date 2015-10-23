<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;

use App\Models\Test;
use App\Models\Website;

class TestController extends ApiController
{
    private $user;
    
    function __construct(Request $request)
    {
        //TODO authentication with some kind of token
        $this->user = new \stdClass();
        $this->user->id = 1;
    }

    public function storeTests(Request $request)
    {
        $tests = $request->get('data');        
        $websiteID = $request->get('website_id');        
        
        $this->checkWebsiteOwner($websiteID);
        
        foreach ($tests as $key => &$test)
        {
            //quick check
            if (empty($test['from']) || empty($test['to']) || empty($test['title']))
            {
                unset($tests[$key]);
                continue;
            }
            
            //create or find existing
            if (isset($test['id']) && $test['id'] > 0)
            {
                $testInDB = Test::find($test['id']);
            }
            else
            {
                $testInDB = new Test(['website_id' => $websiteID]);
            }
            $testInDB->title = $test['title'];
            $testInDB->enabled = 1;
            $testInDB->test_element = $test['from'];
            
            if (starts_with($test['to'], 'image/'))
            {
                $testInDB->element_type = 'image';
            }
            else
            {
                $testInDB->element_type = 'text';
            }
            $testInDB->test_variation = $test['to'];
            $testInDB->conversion_type = 'click';
            $testInDB->conversion_element = $test['conversion'];
            $testInDB->goal_type = 'conversions';//$test['goal_type'];
            $testInDB->goal = 1000;
            $testInDB->start = '';
            $testInDB->end = '';
            
            $testInDB->save();
            
            Website::find($websiteID)->touch();
            
            $test['id'] = $testInDB->id;
        }
        return self::respondSuccess($tests);
    }
    
    public function loadTests(Request $request)
    {
        $websiteID = $request->get('website_id');
        
        $this->checkWebsiteOwner($websiteID);
        
        $tests = Test::where('website_id', $websiteID)
                ->orderBy('updated_at', 'desc')
                ->get();
        
        return self::respondSuccess($tests);
    }
   
}
