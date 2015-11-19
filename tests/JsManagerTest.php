<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;

class JsManagerTest extends Selenium
{
    /*dont forget to:
     * https://www.digitalocean.com/community/tutorials/how-to-install-java-on-ubuntu-with-apt-get
     * java -jar selenium.jar
     */

    use Laravel;

    function testTestManager()
    {        
        $this->userLogin()->seePageIs('/dashboard');/*visit('website/show/' . $website->id)
            ->click('Manage tests')
            ->see('Tadas\' blog');*/
    }
    
    function userLogin()
    {
        return $this->visit('/auth/login')
            ->type('tadaspaplauskas@gmail.com', 'email')
            ->type('paplauskas', 'password')
            ->press('Log in');//->waitForElement('menu-top'); //gotta wait, its a real browser after all
    }
        
        
}
