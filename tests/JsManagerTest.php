<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Laracasts\Integrated\Extensions\Selenium;
use Laracasts\Integrated\Services\Laravel\Application as Laravel;
use Illuminate\Support\Facades\DB;

class JsManagerTest extends Selenium
{
    use Laravel;

    function testTestManager()
    {
        $this->userLogin()
            ->seePageIs('/dashboard')
            ->click('Websites')
            ->click('For testing')
            ->click('Manage tests')
            ->wait(5000)
            
            //first test (text)
            ->click('+ Add new')
            ->type('first test title', '.active .test-title')
            ->click('.active .abtl-pick-element')
            ->click('Little big things')
            ->type('This is blog desc that supports <b>html</b>', '.active .abtl-test-text')
            ->click('.active .abtl-conversion-type option[value=time]')
                
            //second test (picture from web)
            ->click('+ Add new')
            ->type('second test picture url', '.active .test-title')
            ->click('.active .abtl-pick-element')
            ->click('.test-image')
            ->type('https://www.google.com/images/nav_logo242.png', '.active .abtl-image-url')
            ->click('.active .abtl-cutom-style-button')
            ->type(' dummy-class', '.active .custom-style-classes')
            ->type('border: 10px solid red;', '.active .custom-style-css')
            ->click('.active .custom-style-close-button')
        
            //third test (picture from upload)
            ->click('+ Add new')
            ->type('third test upload', '.active .test-title')
            ->click('.active .abtl-pick-element')
            ->click('.test-image-upload')
            ->click('.active .upload-or-url option[value=upload]')
            ->attachFile('.active [name=inputImage]', __DIR__ . '/sample.jpg')
            ->click('.active .abtl-custom-conversion-button')
            ->click('#19 how to get better chroma key with Sony Vegas')
                
            //check save/publish buttons
            ->click('Save')
            ->wait(5000)
            ->seeInAlert('Saved successfully')
            ->click('Publish')
            ->wait(5000) //redirect            
            ->see('first test title')
            ->see('second test picture url')
            ->see('third test upload')
            ->visit('/users/c4ca4238a0b923820dcc509a6f75849b/c51ce410c124a10e0db5e4b97fc2af39/tests.js')
            ->see('This is blog desc that supports');
        
        DB::delete('delete from tests where website_id IN (SELECT id FROM websites WHERE user_id IN(SELECT id FROM users WHERE email="tadaspaplauskas@gmail.com"))');
    }

    function userLogin()
    {
        return $this->visit('/auth/login')
            ->type('tadaspaplauskas@gmail.com', 'email')
            ->type('paplauskas', 'password')
            ->press('Log in');
    }
}
