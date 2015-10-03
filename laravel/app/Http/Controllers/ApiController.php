<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Test;
use App\Models\Website;

class ApiController extends Controller
{
    private $user;
    
    function __construct()
    {
        //TODO authentication with some kind of token
        $this->user = new \stdClass();
        $this->user->id = 1;
    }

    public function save(Request $request)
    {
        //print_r($request->all());die;
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
            if (isset($test['id']))
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
            
            $test['id'] = $testInDB->id;
        }
        return self::respondSuccess($tests);
    }
    
    public function load(Request $request)
    {
        $websiteID = $request->get('website_id');
        
        $this->checkWebsiteOwner($websiteID);
        
        $tests = Test::where('website_id', $websiteID)
                ->orderBy('updated_at', 'desc')
                ->get();
        
        return self::respondSuccess($tests);
    }    
    
    /*************** HELPERS ***********/
    
    public function checkWebsiteOwner($websiteID)
    {
        $website = Website::find($websiteID) or self::respondError('Website not found');

        if ($website->user_id !== $this->user->id)
        {
            self::respondError('not your website');
        }
    }
    
    public static function respondError($msg = 'error')
    {
        die(json_encode(['message' => $msg]));
    }
    
    public static function respondSuccess($data = [])
    {
        return json_encode($data);
    }
}
