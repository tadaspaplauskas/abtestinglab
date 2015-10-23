<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\ApiController;

use App\Models\Visitor;

class VisitorController extends ApiController
{
    private $user;
    
    function __construct()
    {
        //TODO authentication with some kind of token
        $this->user = new \stdClass();
        $this->user->id = 1;
    }
    
    public function newVisitor(Request $request)
    {
        //check host, if it comes from the right website
        if ($request->has('website_id'))
        {
            $visitor = Visitor::create([
                //'hash' => DB::raw('MD5(id)'),
                'ip' => $request->ip(),
                'website' => $request->get('website_id'),
                'user_agent' => $request->server('HTTP_USER_AGENT')]);

            return $visitor->id;
            //return Visitor::find($visitor->id)->hash;
        }
    }
}
