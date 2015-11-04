<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Website;
use App\Models\Test;
use MatthiasMullie\Minify;


class TestController extends Controller
{
    private $user;

    function __construct()
    {
        $this->user = Auth::user();
    }

    public function changePublicStatus($id)
    {
       $test = Test::find($id);

        if ($test->website->user_id === $this->user->id)
        {
            if ($test->enabled == 0)
            {
                $test->enabled = 1;
            }
            else if ($test->enabled == 1)
            {
                $test->enabled = 0;
            }
            $test->save();
        }
        return redirect()->back();
    }

    public function changeArchiveStatus($id)
    {
       $test = Test::find($id);

        if ($test->website->user_id === $this->user->id)
        {
            if ($test->archived == 0)
            {
                $test->archived = 1;
                $test->enabled = 0;
            }
            else if ($test->archived == 1)
            {
                $test->archived = 0;
            }
            $test->save();
        }
        return redirect()->back();
    }

    public function delete($id)
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
    }

    public function destroy(Request $request)
    {
        $test = Test::find($request->get('test_id'));
        $websiteID = $test->website->id;

        if ($test->website->user_id === $this->user->id)
            $test->delete();

        Session::flash('success', 'Test deleted.');
        return redirect('website/show/' . $websiteID);
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

        $jsPath = $website->jsPath();
        $return = file_put_contents($jsPath, view('js.manager', [
            'website' => $website, 'tests' => $returnValue]), LOCK_EX);
        
        if ($return)
            return $this->minifyJS($jsPath);
        else
            return false;
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

        $jsPath = $website->jsPath();
        $return = file_put_contents($jsPath, view('js.visitor', [
            'website' => $website, 'tests' => $returnValue]), LOCK_EX);
        
        if ($return)
            return $this->minifyJS($jsPath);
        else
            return false;
    }
    
    public function minifyJS($jsPath)
    {
        //minify first
        $minifier = new Minify\JS($jsPath);
        $return = $minifier->minify($jsPath);
        //gzip if success
        if ($return)
        {
            $return = $minifier->gzip($jsPath . '.gz', 9);
            return true;
        }
        else
        {
            return false;
        }
    }

}