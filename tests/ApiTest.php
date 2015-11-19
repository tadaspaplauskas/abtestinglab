<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class ApiTest extends TestCase
{
    /* TESTING API CALLS WITH TOKEN MIDDLEWARE */

    use DatabaseTransactions;
    
    public $website;
    public $user;
    
    public function testLoadTests()
    {
        
        $this->manager()
            ->call('POST', '/api/load', ['website_id' => $this->website->id], [], [],
            $this->serverHeaders());

        $this->assertResponseOk();
        $this->seeJson([]);
    }

    public function manager()
    {
        $this->user = $user = factory(App\User::class)->create();
        $this->website = $website = factory(App\Models\Website::class)
                ->create(['user_id' => $user->id]);
        
        $this->actingAs($user)
            ->call('GET', '/tests/manager/' . $website->id);

        return $this;
    }

    public function serverHeaders()
    {
        $token = DB::select('select token from websites where id = ?', 
            [$this->website->id])[0]->token;
        return ['HTTP_token' => $token, 'HTTP_website_id' => $this->website->id];
    }

}
