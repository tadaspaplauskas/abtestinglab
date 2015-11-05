<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;
use App\Models\Test;
use App\Models\Visitor;

class VisitorController extends ApiController
{
    private $user;
    
    function __construct()
    {
        //TODO authentication with some kind of token
        $this->user = new \stdClass();
        $this->user->id = 1;
    }
    
    public function newVisitor(Request $request)
    {
        //check host, if it comes from the right website
        if ($request->has('website_id'))
        {
            $visitor = Visitor::create([
                //'hash' => DB::raw('MD5(id)'),
                'ip' => $request->ip(),
                'website' => $request->get('website_id'),
                'user_agent' => $request->server('HTTP_USER_AGENT')]);

            return $visitor->id;
            //return Visitor::find($visitor->id)->hash;
        }
    }
    
    public function logVisit(Request $request)
    {
        //get all current tests from visitor
        $newTests = $request->get('tests');
        if (is_null($newTests))
            return false;
        
        $visitor = Visitor::find($request->get('visitor_id'));

        //get all saved and accounted tests for visitor
        if (!empty($visitor->tests))
            $oldTests = json_decode($visitor->tests, true);
        else
            $oldTests = [];
        
        $oldKeys = array_keys($oldTests);
        foreach($newTests as $testID => $variation)
        {
            //is the test is not yet accounted for, do that
            if (!in_array($testID, $oldKeys))
            {
                $test = Test::find($testID);
                if ($variation === 'a')
                    $test->original_pageviews++;
                else if ($variation === 'b')
                    $test->variation_pageviews++;

                $test->save();
            }
        }
        $visitor->tests = json_encode($newTests);
        $visitor->save();
    }
}
