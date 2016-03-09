<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use DB;

use App\Http\Controllers\API\ApiController;
use App\Models\Test;
use App\Models\Visitor;
use App\Http\Controllers\TestController as MainTestController;

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
            return $this->respondSuccess();

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
                $test->timestamps = false;

                $user = $test->website->user;
                //check if user has enough reach
                if (!$user->paid())
                    return $this->respondSuccess();

                //if smth fucks up the fuck it all
                DB::beginTransaction();

                if (isset($test->id))
                {
                    if ($test->isEnabled())
                    {
                        if ($variation === 'a')
                            $test->original_pageviews++;
                        else if ($variation === 'b')
                            $test->variation_pageviews++;

                        $test->save();

                        event(new \App\Events\LogNewVisit($user));
                    }

                    //goal is reached
                    if ($test->totalReach() >= $test->goal)
                    {
                        $test->disable();
                        $testController = new MainTestController();
                        $testController->refreshTestsJS($test->website);

                        event(new \App\Events\TestCompleted($test));
                    }
                }
                DB::commit();
            }
        }
        $visitor->tests = json_encode($newTests);
        $visitor->save();

        return $this->respondSuccess();
    }
}
