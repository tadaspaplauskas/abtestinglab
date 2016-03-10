<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FileController;

use App\Models\Test;
use App\Models\Website;
use App\Http\Controllers\TestController as TestService;

class TestController extends ApiController
{
    function __construct()
    {
    }

    public function storeTests(Request $request, TestService $testService)
    {
        if ($request->has('data'))
            $tests = $request->get('data');
        else
            $tests = [];
        //print_r($tests);
        $websiteID = $request->get('website_id');

        $website = Website::find($websiteID);

        $testsToSave = [];

        if (count($tests) > 0)
        {
            foreach ($tests as $key => &$test)
            {
                DB::beginTransaction();

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
                    $testInDB = Test::create(['website_id' => $websiteID]);
                }
                $testInDB->title = $test['title'];

                /*if ($testInDB->totalReach() < $test['goal'])
                    $testInDB->status = 'enabled';*/

                $testInDB->test_element = $test['from'];

                if ($testInDB->test_variation !== $test['to'])
                {
                    //if base64 encoded image
                    if (starts_with($test['to'], 'data:image/jpeg;base64')
                        || starts_with($test['to'], 'data:image/png;base64'))
                    {
                        $base64 = str_replace(['data:image/jpeg;base64,', 'data:image/png;base64,'], '', $test['to']);
                        $base64 = base64_decode($base64);

                        FileController::makeImage($base64, $testInDB->imagePath());

                        $testInDB->test_variation = $testInDB->imageUrl();
                        $testInDB->element_type = 'image';
                    }
                    //if no base64, but still an image
                    else if ($test['image_url'] == true)
                    {
                        $testInDB->element_type = 'image';
                        $testInDB->test_variation = $test['to'];
                    }
                    else
                    {
                        $testInDB->element_type = 'text';
                        $testInDB->test_variation = $test['to'];
                    }
                }
                //style attributes
                $attr = [];
                if (!empty($test['attributes']['class']))
                    $attr['class'] = $test['attributes']['class'];
                if (!empty($test['attributes']['style']))
                    $attr['style'] = $test['attributes']['style'];

                if (!empty($attr))
                    $testInDB->attributes = json_encode($attr);

                //CONVERSION HANDLING
                if ($test['conversion']['type'] === 'time')
                {
                    $testInDB->conversion_type = 'time';
                    $testInDB->conversion_element = $test['conversion']['conversion'];
                }
                else
                {
                    $testInDB->conversion_type = 'click';
                    $testInDB->conversion_element = $test['conversion']['conversion'];
                }

                // SAVE GOAL
                $testInDB->goal_type = 'views'; // hardcoded for now. Can be 'conversions' too.
                $testInDB->goal = $test['goal'];

                $testInDB->save();

                $website->touch();

                $test['id'] = $testInDB->id;
                $testsToSave[] = $testInDB->id;

                DB::commit();
            }

            $testService->refreshTestsJS($website);

            event(new \App\Events\TestsModified($website->user));

            Test::where('website_id', $websiteID)->whereNotIn('id', $testsToSave)->delete();
        }
        return $this->respondSuccess($tests);
    }

    public function loadTests(Request $request)
    {
        $websiteID = $request->get('website_id');

        $tests = Test::NotArchived()
                ->where('website_id', $websiteID)
                ->orderBy('updated_at', 'asc')
                ->get();

        return $this->respondSuccess($tests);
    }

}
