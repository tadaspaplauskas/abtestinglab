<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;

class BackendTest extends TestCase
{

    use DatabaseTransactions;

    public function testLoginAndLogout()
    {
        $this->visit('/')
        ->click('Log in')
        ->seePageIs('/login')
        ->type('tadaspaplauskas@gmail.com', 'email')
        ->type('password', 'password')
        ->press('Log in')
        ->see('Recently completed tests')
        ->visit('logout')
        ->seePageIs('/');
    }

    public function testAddEditDeleteWebpage()
    {
        $user = App\User::find(1); // used only for testing

        $this->actingAs($user)
            ->visit('/websites')
            ->click('Add a new one')
            ->type('http://abtestinglab.dev/blog.html', 'url')
            ->type('Testy test', 'title')
            ->press('Save')
            ->see('Success!')
            ->click('Done')
            ->click('Edit website')
            ->type('test2', 'title')
            ->press('Save')
            ->see('Success')
            ->click('Delete website')
            ->see('Are you sure?')
            ->press('Delete test2')
            ->see('Success')
            ->cleanup($user);
    }

    public function cleanup($user)
    {
        File::deleteDirectory($user->path(), true);
    }
}
