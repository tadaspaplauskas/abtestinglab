<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;

class ApiTest extends TestCase
{
    /* TESTING API CALLS WITH TOKEN MIDDLEWARE */
 
    public function testLoad()
    {
        $this->manager()
            ->call('POST', '/api/load', ['website_id' => $this->website], [], [],
            $this->serverHeaders());
        
        $this->assertResponseOk();
        $this->seeJson([]);
    }
    
    public function manager()
    {
        $user = App\User::find($this->user); // used only for testing        
        $this->actingAs($user)
            ->call('GET', '/tests/manager/' . $this->website);
        
        return $this;
    }
    
    public function serverHeaders()
    {
        $token = DB::select('select token from websites where id = ?', [$this->website])[0]->token;
        return ['HTTP_token' => $token, 'HTTP_website_id' => $this->website];
    }
        
}
