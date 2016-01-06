casper.test.begin('Test JS manager', 19, function suite(test) {
    casper.options.viewportSize = {width: 1920, height: 1080};
    //casper.options.verbose = true;
    //casper.options.logLevel ="debug";

        //login
        casper.start("http://abtestinglab.dev/auth/login", function() {
            test.assertTitle("Join in", "Login page title as expected");

            //fill in login data and submit immediately
            this.fill('form', {
                email: "tadaspaplauskas@gmail.com",
                password: "paplauskas"
            });

            this.click('.btn-confirm');
            this.waitForUrl(/dashboard/, null, null, 10000);
        });

        //navigate to websites
        casper.then(function() {
            test.assertTitle('Dashboard', 'Landed on dashboard');
            this.clickLabel('Websites');
        });

        //navigate to website for testing
        casper.then(function() {
            test.assertTitle('Your websites', 'Landed on your websites');
            this.clickLabel('For testing');
        });

        //manage tests
        casper.then(function() {
            test.assertTextExists('Manage tests', 'Manage tests button exists');
            this.click('.btn-primary');
        });

        //check if panel loaded
        casper.then(function() {
            this.waitUntilVisible('#abtl-placeholder', function() {
                test.assertTextExists('Add new', 'Control Panel loaded');
            });
        });

        //add first test (text)
        casper.then(function() {
            this.clickLabel('+ Add new');

            this.fillSelectors('#abtl-placeholder', {
                '.active .test-title': 'first test title'
            });

            //choose element to test
            this.click('.active .abtl-pick-element');

            this.click('.description');

            this.click('.abtl-step-variation');

            test.assertVisible('.active .abtl-test-text', 'Variation field is visible');

            this.evaluate(function() {
                $('.active .abtl-test-text').val('This is blog desc that supports <b>html</b>').keyup();
            });

            this.click('.abtl-step-variation');

            test.assertVisible('.active .abtl-conversion-type', 'Conversion fields are visible');

            this.evaluate(function() {
                $('.active .abtl-conversion-type option').val('time').change();
            });

            test.assertEquals(this.getElementInfo('.description').text, 'This is blog desc that supports html',
                'Test changes visible');
        });

        //add second test (picture from internet)
        casper.then(function() {
            this.clickLabel('+ Add new');

            this.fillSelectors('#abtl-placeholder', {
                '.active .test-title': 'second test picture url'
            });

            //choose element to test
            this.click('.active .abtl-pick-element');
            this.click('.test-image');

            this.click('.abtl-step-variation');
            test.assertVisible('.active .abtl-image-url', 'Image fields are visible');

            this.evaluate(function() {
                $('.active .abtl-image-url').val('https://www.google.com/images/nav_logo242.png').change();
            });

            test.assertEquals(this.getElementInfo('.active .image-upload-preview').attributes.src,
                'https://www.google.com/images/nav_logo242.png',
                'Image preview is loaded');

            test.assertEquals(this.getElementInfo('.test-image').attributes.src,
                'https://www.google.com/images/nav_logo242.png',
                'Changed image is visible');


            this.click('.abtl-step-conversion');

            this.click('.active .abtl-cutom-style-button');

            this.fillSelectors('#abtl-placeholder', {
                '.active .custom-style-classes': 'test-image dummy-class',
                '.active .custom-style-css': 'border: 10px solid red;'
            });

            this.click('.active .custom-style-close-button');

            test.assertMatch(this.getElementInfo('.test-image').attributes.class, /dummy-class/, 'Class modif. works');
            test.assertMatch(this.getElementInfo('.test-image').attributes.style, /border: 10px solid red;/, 'Style modif. works');
        });

        //add third test (upload a picture)
        casper.then(function() {
            this.clickLabel('+ Add new');

            this.fillSelectors('#abtl-placeholder', {
                '.active .test-title': 'third test picture upload'
            });

            //choose element to test
            this.click('.active .abtl-pick-element');
            this.click('.test-image-upload');

            this.click('.abtl-step-variation');

            this.evaluate(function() {
                $('.active .upload-or-url').val('upload').change();
            });

            test.assertVisible('.active .abtl-image-upload', 'Image upload field is visible');

            this.page.uploadFile('.active .abtl-image-upload','panda.jpg');

            this.wait(1000, function() {

                this.evaluate(function() {
                    $('.active .abtl-image-upload').change();
                });

                test.assertVisible('.active .image-upload-preview', 'Upload image preview visible');
            });

        });

        //add custom conversion
        casper.then(function() {
            this.click('.active .abtl-step-conversion');
            this.click('.active .abtl-custom-conversion-button');
            this.clickLabel('#19 how to get better chroma key with Sony Vegas');

            test.assertTextExists('Conversion defined.', 'Conversion chosen.');
        });

        //save everything and see if it exists
        casper.then(function() {
            this.clickLabel('Save all');

            this.wait(3000, function(){
                this.clickLabel('Exit');
                this.wait(3000, function(){
                    this.capture('screen.jpg');
                    test.assertTextExists('first test title', 'First test saved.');
                    test.assertTextExists('second test picture url', 'Second test saved');
                    test.assertTextExists('third test picture upload', 'Third test saved.');
                });
            });


        });

        function testAlert(message) {
            test.assertMatch(message, /Saved successfully/, 'Saved');
            this.capture('screen.jpg');
        }

        casper.run(function() {
            test.done();
        });
    });

/*function testTestManager()
    {

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
    }*/