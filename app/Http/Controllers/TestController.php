<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

use App\Models\Website;
use App\Models\Test;
use App\Http\Controllers\FileController;


class TestController extends Controller
{
    private $user;

    function __construct()
    {
        if (Auth::check())
            $this->user = Auth::user();
    }

    public function changePublicStatus($id)
    {
       $test = Test::find($id);

        if ($test->website->user_id === $this->user->id)
        {
            if ($test->status == 'disabled')
            {
                if ($test->totalReach() < $test->goal)
                {
                    $test->enable();
                }
                else
                {
                    Session::flash('warning', 'Could not enable the test because the goal is already reached. Please adjust the goal or make a new test.');
                    return redirect()->back();
                }
            }
            else if ($test->isEnabled())
            {
                $test->disable();
            }
            $test->save();
        }
        $this->generateTestsJS($test->website);
        return redirect()->back();
    }

    public function changeArchiveStatus($id)
    {
       $test = Test::find($id);

        if ($test->website->user_id === $this->user->id)
        {
            if (!$test->isArchived())
            {
                $test->archive();
            }
            else
            {
                $test->enable();
                $test->nullifyStatistics();
            }
            $test->save();

            $this->generateTestsJS($test->website);
        }
        return redirect()->back();
    }

    /*public function delete($id)
    {
        $website = Test::find($id);

        if (isset($website->id))
        {
            return view('tests.delete', ['test' => $website]);
        }
        else
        {
            return redirect('website/index');
        }
    }*/

    public function destroy($testID)
    {
        $test = Test::find($testID);
        $websiteID = $test->website->id;

        if ($test->website->user_id === $this->user->id)
            $test->delete();

        Session::flash('success', 'Test deleted.');
        return redirect()->back();
    }

    public function publish($websiteID)
    {
        $website = Website::find($websiteID);
        
        if ($website->user->id !== $this->user->id)
            return false;

        if ($this->generateTestsJS($website))
        {
            Session::flash('success', 'Published successfully.');
        }
        else {
            Session::flash('fail', 'Something went wrong, please try again later.');
        }
        return redirect(route('website.show', ['id' => $website->id]));
    }

    public function manager($websiteID)
    {
        $website = Website::find($websiteID);

        if ($website->user->id !== $this->user->id)
            return false;

        $token = self::token();
        $website->token = $token;
        $website->save();

        if ($this->generateManagerJS($website))
        {
            return redirect($website->url . '#token=' . $token);
        }
        else {
            Session::flash('fail', 'Something went wrong, please try again later.');
            return redirect()->back();
        }
    }

    public function generateManagerJS($website)
    {
        $tests = $website->tests;
        $jsTests = [];
        $jsConversions = [];

        foreach($tests as $test)
        {
            //default half 50/100
            $weight = 50;

            if ($test->adaptive)
            {
                //kick in after n conversions. Arbitrary number
                if (($test->variation_conversion_count + $test->original_conversion_count) > self::ADAPTIVE_CONVERSIONS_BOUNDARY)
                {
                    $weight = $test->variation_conversion_count / $test->variation_pageviews;

                    if ($weight > 0.9)
                        $weight = 0.9;
                    else if ($weight < 0.1)
                        $weight = 0.9;

                    $weight = $weight * 100;
                }
            }

            $jsTests[] = ['id' => $test->id,
                //'title' => $test->title,
                'element' => $test->test_element,
                'variation' => $test->test_variation,
                'variation_weight' => $weight];

            $jsConversions[] = [
                'test_id' => $test->id,
                'element' => (!empty($test->conversion_element) ? $test->conversion_element : $test->test_element),
                ];
        }

        $returnValue = ['tests' => $jsTests, 'conversions' => $jsConversions];

        $jsPath = $website->jsPath();

        return FileController::put($jsPath, view('js.manager', [
            'website' => $website, 'tests' => $returnValue]), true);

    }

    public function generateTestsJS($website)
    {
        $tests = $website->enabledTests;
        $jsTests = [];
        $jsConversions = [];
        $jsPath = $website->jsPath();

        if ($tests->isEmpty())
        {
            $content = '';
        }
        else
        {
            foreach($tests as $test)
            {
                //default half 50/100
                $weight = 50;

                if ($test->adaptive)
                {
                    //kick in after n conversions. Arbitrary number
                    if (($test->variation_conversion_count + $test->original_conversion_count) > self::ADAPTIVE_CONVERSIONS_BOUNDARY)
                    {
                        $weight = $test->variation_conversion_count / $test->variation_pageviews;

                        if ($weight > 0.9)
                            $weight = 0.9;
                        else if ($weight < 0.1)
                            $weight = 0.9;

                        $weight = $weight * 100;
                    }
                }

                $variation = $test->test_variation;

                $jsTests[] = ['id' => $test->id,
                    'element' => $test->test_element,
                    'element_type' => $test->element_type,
                    'variation' => $variation,
                    'attributes' => json_decode($test->attributes),
                    'variation_weight' => $weight];

                $jsConversions[] = [
                    'test_id' => $test->id,
                    'conversion_type' => $test->conversion_type,
                    'element' => (!empty($test->conversion_element) ? $test->conversion_element : $test->test_element),
                    ];
            }

            $returnValue = ['tests' => $jsTests, 'conversions' => $jsConversions];

            $content = view('js.visitor', ['website' => $website, 'tests' => $returnValue]);
        }

        $return = FileController::put($jsPath, $content, true);

        $website->published_at = Carbon::now();
        $website->save();

        return $return;
    }

}