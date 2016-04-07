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
                    Session::flash('warning', 'Could not enable the test because the goal is already reached. Please adjust the reach or make a new test.');
                    return redirect()->back();
                }
            }
            else if ($test->isEnabled())
            {
                $test->disable();
            }
            $test->save();
        }
        if ($this->refreshTestsJS($test->website))
            Session::flash('success', 'Changes were saved.');
        else
            Session::flash('fail', 'Something went wrong, please try again later.');

        event(new \App\Events\TestsModified($this->user));

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
            if ($this->refreshTestsJS($test->website))
                Session::flash('success', 'Changes were saved and published.');
            else
                Session::flash('fail', 'Something went wrong, please try again later.');

            event(new \App\Events\TestsModified($this->user));
        }

        return redirect()->back();
    }

    public function destroy($testID)
    {
        $test = Test::find($testID);
        $websiteID = $test->website->id;

        if ($test->website->user_id === $this->user->id)
            $test->delete();

        event(new \App\Events\TestsModified($this->user));

        Session::flash('success', 'The test was deleted.');
        return redirect()->back();
    }

    public function manager(Website $website)
    {
        //check if script exists
        if (!$website->isScriptOnline())
        {
            session()->flash('fail', 'Our script is not installed on the website or the link you provided is unreachable. You can find instructions <a href="'. route('websites.instructions', [$website->id]) .'">here</a>.');
            return redirect()->back();
        }

        $token = self::token();
        $website->token = $token;
        $website->save();

        if ($this->generateManagerJS($website))
        {
            if (substr_count($website->url, '/') > 2)
                return redirect($website->url . '#token=' . $token);
            else
                return redirect($website->url . '/#token=' . $token);
        }
        else
        {
            session()->flash('fail', 'Something went wrong, please try again later.');
            return redirect()->back();
        }
    }

    public function managerExit($websiteID)
    {
        $website = Website::find($websiteID);

        if ($website->user->id !== $this->user->id)
            return $this->respondError();

        $website->token = '';
        $website->save();

        $this->refreshTestsJS($website);

        event(new \App\Events\TestsModified($this->user));

        session()->flash('success', 'All good');

        return redirect(route('websites.show', $website->id));
    }

    public function refreshTestsJS($website)
    {
        if (empty($website->token))
        {
            return $this->generateTestsJS($website);
        }
        else
        {
            return $this->generateManagerJS($website);
        }
    }

    public function generateManagerJS($website)
    {
        $returnValue = $this->testsArray($website);

        $jsPath = $website->jsPath();
        $return = FileController::put($jsPath, view('js.manager', [
            'website' => $website, 'tests' => $returnValue])->render());

        return $return;
    }

    public function generateTestsJS($website)
    {
        $jsPath = $website->jsPath();
        $content = '';

        $returnValue = $this->testsArray($website);

        if (!is_null($returnValue))
        {
            $content = view('js.visitor', ['website' => $website, 'tests' => $returnValue]);
        }

        $return = FileController::put($jsPath, $content);

        return $return;
    }

    public function testsArray($website)
    {
        $applyTests = [];
        $trackConversions = [];
        $applyFinished = [];

        $tests = $website->tests()->notArchived()->get();
        if ($tests->isEmpty())
            return null;

        foreach($tests as $test)
        {
            //if the option is set to keep winning variation after
            //the test is over
            if ($website->keep_best_variation && $test->isDisabled())
            {
                //variation wins
                if ($test->convDiff() > 0)
                {
                    $applyFinished[] = ['id' => $test->id,
                        'element' => $test->test_element,
                        'element_type' => $test->element_type,
                        'variation' => $test->test_variation,
                        'attributes' => json_decode($test->attributes),
                    ];
                }
                //if control wins - do nothing, it's already there
            }

            $applyTests[] = ['id' => $test->id,
                'enabled' => $test->isEnabled() ? 1 : 0,
                'element' => $test->test_element,
                'element_type' => $test->element_type,
                'variation' => $test->test_variation,
                'attributes' => json_decode($test->attributes),
                'variation_weight' => $test->getWeight(),
                ];

            $trackConversions[] = [
                'test_id' => $test->id,
                'conversion_type' => $test->conversion_type,
                'element' => (!empty($test->conversion_element) ? $test->conversion_element : $test->test_element),
                ];

        }
        return [
            'tests' => $applyTests,
            'conversions' => $trackConversions,
            'finished' => $applyFinished,
            ];
    }

}