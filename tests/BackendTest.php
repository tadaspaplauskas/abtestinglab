<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\File;

class BackendTest extends TestCase
{

    use DatabaseTransactions;

    public function testRegisterLoginAndLogout()
    {
        $this->visit('/')
        ->click('Log in')
        ->seePageIs('/auth/login')
        ->type('tadaspaplauskas@gmail.com', 'email')
        ->type('paplauskas', 'password')
        ->press('Log in')
        ->see('Dashboard')
        ->visit('auth/logout')
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
