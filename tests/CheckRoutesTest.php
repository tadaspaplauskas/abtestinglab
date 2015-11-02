<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CheckRoutesTest extends TestCase
{
    public function testRouteDashboard()
    {
        $user = factory(App\User::class)->create();
        
        $this->actingAs($user)
            ->visit('dashboard')
            ->assertResponseStatus(200);
    }
    
    public function testRouteWebsites()
    {
        $user = factory(App\User::class)->create();
        
        $this->actingAs($user)
            ->visit('website/index')
            ->assertResponseStatus(200);
    }
    
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
