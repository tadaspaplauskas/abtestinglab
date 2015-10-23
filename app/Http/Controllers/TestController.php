<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use App\Http\Controllers\Controller;

use App\Models\Website;
use App\Models\Test;


class TestController extends Controller
{
    private $user;

    function __construct()
    {
        $this->user = Auth::user();
    }

    public function disable($id)
    {
        $test = Test::find($id);

        if ($test->website->user_id === $this->user->id)
        {
            $test->enabled = 0;
            $test->save();
        }
        return redirect()->back();
    }

    public function enable($id)
    {
       $test = Test::find($id);

        if ($test->website->user_id === $this->user->id)
        {
            $test->enabled = 1;
            $test->save();
        }
        return redirect()->back();
    }

    public function publish($websiteID)
    {

        $website = Website::where('id', $websiteID)
                ->where('user_id', $this->user->id)
                ->firstOrFail();

        if ($this->generateTestsJS($website))
        {
            $website->published_at = DB::raw('NOW()');
            $website->save();
            Session::flash('success', 'Published successfully.');
        }
        else {
            Session::flash('fail', 'Something went wrong, please try again later.');
        }

        return redirect('website/show/' . $website->id);
    }

    public function manager($website_id)
    {
        $website = Website::where('id', $website_id)
                ->where('user_id', $this->user->id)
                ->firstOrFail();

        $token = self::token();

        $website->token = $token;
        $website->save();

        if ($this->generateManagerJS($website))
        {
            return redirect('http://abtestinglab.dev/blog.html#token=' . $token);
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

        $path = self::jsPath($website);

        $return = file_put_contents($path, view('js.manager', [
            'website' => $website, 'tests' => $returnValue]), LOCK_EX);
        return $return;
    }

    public function generateTestsJS($website)
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

        $path = self::jsPath($website);

        $return = file_put_contents($path, view('js.visitor', [
            'website' => $website, 'tests' => $returnValue]), LOCK_EX);
        return $return;
    }

    /************ HELPERS *********/

    public static function jsPath($website)
    {
        return public_path(self::USERS_PATH
                . $website->user->hash() . '/'
                . $website->hash() . '/'
                . self::JS_FILENAME);
    }

}