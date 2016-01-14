<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\API\ApiController;
use App\Models\Test;
use App\Models\Visitor;

class VisitorController extends ApiController
{
    private $user;

    function __construct()
    {
    }

    public function newVisitor(Request $request)
    {
        //check host, if it comes from the right website
        if ($request->has('website_id'))
        {
            $visitor = Visitor::create([
                'ip' => $request->ip(),
                'website_id' => $request->get('website_id'),
                'user_agent' => $request->server('HTTP_USER_AGENT')]);

            return $visitor->id;
        }
        return $this->respondError();
    }

    public function logVisit(Request $request)
    {
        //get all current tests from visitor
        $newTests = $request->get('tests');
        if (is_null($newTests))
            return false;

        $visitor = Visitor::find($request->get('visitor_id'));

        if (!isset($visitor->id))
        {
            return $this->respondError('Visitor does not exist');
        }
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

                $user = $test->website->user;
                //check if user has enough reach
                if (!$user->paid())
                    return $this->respondSuccess();

                //if smth fucks up the fuck it all
                DB::beginTransaction();

                if (isset($test->id))
                {
                    if ($variation === 'a')
                        $test->original_pageviews++;
                    else if ($variation === 'b')
                        $test->variation_pageviews++;

                    $test->save();

                    event(new \App\Events\LogNewVisit($user));
                }
                DB::commit();
            }
        }
        $visitor->tests = json_encode($newTests);
        $visitor->save();

        return $this->respondSuccess();

    }
}
