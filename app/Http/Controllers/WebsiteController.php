<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Website;
use App\User;
use Session;
use App\Http\Controllers\TestController;

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

            Session::flash('success', 'Website updated.');
        }
        else
        {
            $website = Website::create($request);
            self::createWebsitePath($website);

            Session::flash('success', 'Website created.');
        }

        return redirect(route('websites.show', ['id' => $website->id]));
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

        Session::flash('success', 'Website deleted.');
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

        Session::flash('success', 'All tests are stopped for this websites.');
        return redirect()->back();
    }

    /*********** HELPERS ****************/

    public static function createWebsitePath($website)
    {
        $return = mkdir(public_path(User::USERS_PATH
                . $website->user->hash() . '/'
                . $website->hash()));

        if ($return)
        {
            mkdir(public_path(User::USERS_PATH
                . $website->user->hash() . '/'
                . $website->hash() . '/images'));
        } else {
            return false;
        }
        return true;
    }
}
