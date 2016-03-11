<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Website;
use App\User;
use Session;
use App\Http\Controllers\TestController;
use App\Models\Developer;

class WebsiteController extends Controller
{
    function __construct()
    {
        $this->user = Auth::user();
    }

    public function index()
    {
        return view('websites.index', ['websites' => $this->user->websites]);
    }

    public function create()
    {
        return view('websites.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'url' => 'required|url',//active_url
        ]);

        $request = $request->all();
        $request['user_id'] = $this->user->id;
        $request['keep_best_variation'] = isset($request['keep_best_variation']);

        /*$request['url'] = str_replace(['http://www.', 'https://www.',
            'http://', 'https://'], '', $request['url']);*/

        if (is_numeric($request['website_id']))
        {
            $website = Website::find($request['website_id']);
            $website->update($request);
            $website->save();

            Session::flash('success', 'The website was updated.');

            return redirect(route('websites.show', ['id' => $website->id]));
        }
        else
        {
            $website = Website::create($request);
            self::createWebsitePath($website);

            Session::flash('success', 'The website was created.');

            return redirect(route('websites.instructions', ['id' => $website->id]));
        }
    }

    public function installInstructions(Website $website)
    {
        return view('websites.install_instructions', compact('website'));
    }

    public function sendInstructions(Website $website, Request $request, Developer $developer)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
        ]);

        $data = $request->all();

        $data['website_id'] = $website->id;
        $data['user_id'] = $this->user->id;

        $newDev = $developer->create($data);

        event(new \App\Events\DeveloperInstructionsSent($newDev));

        session()->flash('success', 'Instructions will be sent to your developer shortly.');

        return redirect(route('websites.show', [$website->id]));
    }

    public function show($id)
    {
        $website = Website::where('id', $id)
                ->where('user_id', $this->user->id)
                ->firstOrFail();

        return view('websites.show', compact('website'));
    }

    public function showArchived($id)
    {
        $website = Website::where('id', $id)
                ->where('user_id', $this->user->id)
                ->firstOrFail();

        return view('websites.show_archived', compact('website'));
    }

    public function edit($id)
    {
        $website = Website::find($id);

        if (isset($website->id))
        {
            return view('websites.edit', ['website' => $website]);
        }
        else
        {
            return redirect(route('websites.index'));
        }
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function delete($id)
    {
        $website = Website::find($id);

        if (isset($website->id))
        {
            return view('websites.delete', ['website' => $website]);
        }
        else
        {
            return redirect(route('websites.index'));
        }
    }

    public function destroy(Request $request)
    {
        Website::where('id', $request->get('website_id'))
                ->where('user_id', $this->user->id)
                ->delete();

        Session::flash('success', 'The website was deleted.');
        return redirect(route('websites.index'));
    }

    public function stopAllTesting($id)
    {
        $website = Website::where('id', $id)
                ->where('user_id', $this->user->id)
                ->first();

        $website->disableTests();

        $tests = new TestController;
        $tests->refreshTestsJS($website);

        Session::flash('success', 'All tests are stopped for this website.');
        return redirect()->back();
    }

    public function managerRedirect($url, Website $website)
    {
        $found = $website->where('url', 'LIKE', '%' . $url . '%')->first();

        /*if (is_null($found))
        {
            session()->flash('warning', 'The website is not yet added');
            return redirect(route('websites.create'))->withInput(['url' => 'http://' . $url]);
        }
        else */
        if (is_null($found) || $found->user_id !== $this->user->id)
        {
            \App::abort(404, 'Sorry, the website you asked for is not available.');
        }
        else
        {
            return redirect(route('websites.show', [$found->id]));
        }
    }

    /*********** HELPERS ****************/

    public static function createWebsitePath($website)
    {
        $return = mkdir(public_path(User::USERS_PATH
                . $website->user->hash() . '/'
                . $website->hash()), 0777, true);

        if ($return)
        {
            mkdir(public_path(User::USERS_PATH
                . $website->user->hash() . '/'
                . $website->hash() . '/images'), 0777, true);
        } else {
            return false;
        }
        return true;
    }
}
