<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;

class ApiTest extends TestCase
{
    /* TESTING API CALLS WITH TOKEN MIDDLEWARE */

    use DatabaseTransactions;

    public $website;
    public $user;
    public $test;
    public $visitor;

    public function testLoadTests()
    {
        $this->manager()->call('POST', '/api/load', [
                'website_id' => $this->website->id,
            ], [], [], $this->serverHeaders());
        
        $this->assertResponseOk();
        $this->seeJson([]);
    }

    public function testSaveTests()
    {
        $this->manager()->call('POST', '/api/save', json_decode('
            {"data":[
                {
                "id":"{backend id}",
                "tab":"abtl-test-1",
                "title":"First test",
                "from":"Tadas\' blog",
                "to":"title",
                "conversion":{
                    "type":"time",
                    "conversion":"15"
                },
                "goal":"500",
                "image_url":"0",
                "attributes":{
                    "class":"",
                    "style":""
                    }
                },{
                "id":"{backend id}",
                "tab":"abtl-test-2",
                "title":"Second test paragraph",
                "from":"This week I took some stock footage of hand gestures for touchscreens in front of a green screen. I had some trouble chroma keying the footage, but after some fiddling I found a pretty good technique to get it to work. This is more of a reminder to myself and a short tutorial for fellow editors struggling with chroma keying in Sony Vegas.","to":"This is into to the blog post.",
                "conversion":{
                    "type":"click",
                    "conversion":"http:\/\/blog.paplauskas.lt\/post\/124397350850\/19-how-to-get-better-chroma-key-with-sony-vegas"
                },
                "goal":"1000",
                "image_url":"0",
                "attributes":{
                    "class":"",
                    "style":""
                }
                }],"website_id":"' . $this->website->id . '"
                }
            ', true), [], [], $this->serverHeaders());
        
        $this->assertResponseOk();
        $this->seeJson([]);
    }
    
    /*********** visitor part ***********/
    
    public function testSaveConversion()
    {
        $this->manager()->call('POST', '/api/save_conversion', json_decode('{
            "test_id": ' . $this->test->id . ', 
            "variation": "b", 
            "visitor_id": ' . $this->visitor->id . ' 
            }', true));
        
        $this->assertResponseOk();
    }
    
    public function testNewVisitor()
    {
        $this->manager()->call('POST', '/api/new_visitor',
            ['website_id' => $this->website->id]);
        
        $this->assertResponseOk();
    }
    
    public function testLogVisit()
    {
        $this->manager()->call('POST', '/api/log_visit', [
            'visitor_id' => $this->visitor->id,
            'tests' => [
                $this->test->id => 'a',
            ]]);
        $this->assertResponseOk();
    }

    public function manager()
    {
        $this->user = $user = factory(App\User::class)->create();
        $this->website = $website = factory(App\Models\Website::class)
                ->create(['user_id' => $user->id]);
        $this->test = factory(App\Models\Test::class)
                ->create(['website_id' => $website->id]);
        $this->visitor = factory(App\Models\Visitor::class)
                ->create(['website_id' => $website->id]);

        $this->actingAs($user)
            ->call('GET', '/tests/manager/' . $website->id);

        return $this;
    }

    public function serverHeaders()
    {
        $this->website = App\Models\Website::find($this->website->id);
        $token = $this->website->token;
        return ['HTTP_token' => $token, 'HTTP_website-id' => $this->website->id];
    }

}
