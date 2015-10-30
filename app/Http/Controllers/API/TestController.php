<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;
use Image;

use App\Models\Test;
use App\Models\Website;

class TestController extends ApiController
{
    function __construct()
    {
    }

    public function storeTests(Request $request)
    {
        if ($request->has('data'))
            $tests = $request->get('data');
        else
            $tests = [];
        
        $websiteID = $request->get('website_id');

        $this->checkWebsiteOwner($websiteID);

        $website = Website::find($websiteID);

        $testsToSave = [];

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
                $testInDB = Test::create(['website_id' => $websiteID]);
            }
            $testInDB->title = $test['title'];
            $testInDB->enabled = 1;
            $testInDB->test_element = $test['from'];

            if ($testInDB->test_variation !== $test['to'])
            {
                //if base64 encoded image
                if (starts_with($test['to'], 'data:image/jpeg;base64')
                    || starts_with($test['to'], 'data:image/png;base64'))
                {
                    $base64 = str_replace(['data:image/jpeg;base64,', 'data:image/png;base64,'], '', $test['to']);
                    $base64 = base64_decode($base64);
                    $img = Image::make($base64);

                    $imagePath = $testInDB->imagePath();

                    // not for now, but probably should in the future
                    // $img->resize(self::ONE_SIZE_WIDTH, self::ONE_SIZE_HEIGHT, function ($constraint){$constraint->aspectRatio();});
                    $umask = umask(0);
                    $img->save($imagePath);
                    chmod($imagePath, 0664);
                    umask($umask);

                    $testInDB->test_variation = $testInDB->imageUrl();
                    $testInDB->element_type = 'image';
                    $testInDB->attributes = json_encode($test['dimensions']);
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
            $testInDB->attributes = json_encode($test['attributes']);
            $testInDB->conversion_type = 'click';
            $testInDB->conversion_element = $test['conversion'];
            $testInDB->goal_type = 'conversions';//$test['goal_type'];
            $testInDB->goal = 1000;
            $testInDB->start = '';
            $testInDB->end = '';

            $testInDB->save();

            $website->touch();

            $test['id'] = $testInDB->id;
            $testsToSave[] = $testInDB->id;
        }
        Test::where('website_id', $websiteID)->whereNotIn('id', $testsToSave)->delete();

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
