<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BackendTest extends TestCase
{
    
    use DatabaseTransactions;
    
    public function testLoginAndLogout()
    {
        $this->visit('/')
        ->click('Sign in')
        ->seePageIs('/auth/login')
        ->type('tadaspaplauskas@gmail.com', 'email')
        ->type('paplauskas', 'password')
        ->press('Sign in')
        ->see('Dashboard')
        ->visit('auth/logout')
        ->seePageIs('/');
    }
    
    public function testAddEditDeleteWebpage()
    {
        $user = App\User::find(1); // used only for testing
        
        $this->actingAs($user)
            ->visit('website/index')
            ->click('Add a new one')
            ->type('http://abtestinglab.dev', 'url')
            ->type('Testy test', 'title')
            ->press('Save')
            ->see('Success!')
            ->click('Edit website')
            ->type('test2', 'title')
            ->press('Save')
            ->see('Success')
            ->click('Delete website')
            ->see('Are you sure?')
            ->press('Delete test2')
            ->see('Success');
    }
    
    public function testEditTests()
    {
        $user = App\User::find(1);
        
        $this->actingAs($user)
            ->visit('website/index')
            ->click('Add a new one')
            ->type('http://abtestinglab.dev', 'url')
            ->type('Testy test', 'title')
            ->press('Save')
            ->see('Success!')
            ->click('Edit website')
            ->type('test2', 'title')
            ->press('Save')
            ->see('Success')
            ->click('Delete website')
            ->see('Are you sure?')
            ->press('Delete test2')
            ->see('Success');
    }
    
    

    /*
    public function testRouteWebsiteShow()
    {
        $user = factory(App\User::class)->create();
        $website = factory(App\Models\Website::class)->create(['user_id' => $user->id]);
        
        $this->actingAs($user)
            ->visit('website/show/'.$website->id)
            ->assertResponseStatus(200);
    }
    /*
    public function testRouteWebsiteArchived()
    {
        $user = factory(App\User::class)->create();
        $website = factory(App\Models\Website::class)->create();
        
        $this->actingAs($user)
            ->visit('website/show/archived/'.$website->id)
            ->assertResponseStatus(200);
    }
    
    public function testRouteWebsiteEdit()
    {
        $user = factory(App\User::class)->create();
        $website = factory(App\Models\Website::class)->create();
        
        $this->actingAs($user)
            ->visit('website/edit/' . $website->id)
            ->assertResponseStatus(200);
    }*/
}
