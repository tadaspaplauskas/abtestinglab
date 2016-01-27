testUrl = 'http://abtestinglab.dev';


casper.test.tearDown(function() {
    //casper.capture('screen.jpg');
    casper.exit();
});

casper.test.begin('Creating user, website, tests', 22, function suite(test) {
    casper.options.viewportSize = {width: 1920, height: 1080};
    //casper.options.verbose = true;
    //casper.options.logLevel ="debug";

    //cleanup
    casper.start(testUrl + "/test_end");

    //register
    casper.thenOpen(testUrl + "/register");

    casper.then(function() {
        test.assertTitle("Sign up", "Registration page title as expected");

        //fill in login data and submit immediately
        this.fill('form', {
            name: "Tester Testerson",
            email: "tester@abtestinglab.com",
            password: "password",
            password_confirmation: "password"
        }, true);

        this.waitForUrl(/websites\/create/, function() {
            test.assertTitle('Add a new website', 'User created');
            this.clickLabel('Log out');
        }, null, 20000);
    });

    //login
    casper.then(function() {

        casper.capture('screen_login.jpg');
        this.clickLabel('Log in');
    });

    casper.then(function() {
        test.assertTitle("Log in", "Login page title as expected");

        //fill in login data and submit immediately
        this.fill('form', {
            email: "tester@abtestinglab.com",
            password: "password"
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
        this.clickLabel('Add a new one');
    });

    //create website for testing
    casper.then(function() {
        this.click('#keep_best_variation');

        this.fill('form', {
            url: "http://abtestinglab.dev/tests/blog.html",
            title: "For testing"
        }, true);

    });

    //prepare test files
    casper.thenOpen(testUrl + "/test_user_created");

    //navigate back
    casper.thenOpen(testUrl + "/websites");

    casper.then(function() {
        this.clickLabel('For testing');
    });

    //manage tests
    casper.then(function() {
        test.assertTextExists('For testing - test list', 'Website created');
        test.assertTextExists('Manage tests', 'Manage tests button exists');
        this.click('.btn-primary');
    });

    //check if panel loaded
    casper.then(function() {
        this.waitUntilVisible('#abtl-placeholder', function() {
            test.assertTextExists('Add new', 'Control Panel loaded');
        }, null, 15000);
    });

    //add first test (text)
    casper.then(function() {
        this.clickLabel('+ Add new');

        this.fillSelectors('#abtl-placeholder', {
            '.abtl-active .abtl-test-title': 'first test title'
        });

        //choose element to test
        this.click('.abtl-active .abtl-pick-element');

        this.click('.description');

        this.click('.abtl-step-variation');

        test.assertVisible('.abtl-active .abtl-test-text', 'Variation field is visible');

        this.evaluate(function() {
            abtl('.abtl-active .abtl-test-text').val('This is blog desc that supports <b>html</b>').keyup();
        });

        this.click('.abtl-step-variation');

        test.assertVisible('.abtl-active .abtl-conversion-type', 'Conversion fields are visible');

        this.evaluate(function() {
            abtl('.abtl-active .abtl-conversion-type option').val('time').change();
        });

        test.assertEquals(this.getElementInfo('.description').text, 'This is blog desc that supports html',
            'Test changes visible');
    });

    //add second test (picture from internet)
    casper.then(function() {
        this.clickLabel('+ Add new');

        this.fillSelectors('#abtl-placeholder', {
            '.abtl-active .abtl-test-title': 'second test picture url'
        });

        //choose element to test
        this.click('.abtl-active .abtl-pick-element');
        this.click('.test-image');

        this.click('.abtl-step-variation');
        casper.capture('debug.jpg');
        test.assertVisible('.abtl-active .abtl-image-url', 'Image fields are visible');

        this.evaluate(function() {
            abtl('.abtl-active .abtl-image-url').val('https://www.google.com/images/nav_logo242.png').change();
        });

        test.assertEquals(this.getElementInfo('.abtl-active .abtl-image-upload-preview').attributes.src,
            'https://www.google.com/images/nav_logo242.png',
            'Image preview is loaded');

        test.assertEquals(this.getElementInfo('.test-image').attributes.src,
            'https://www.google.com/images/nav_logo242.png',
            'Changed image is visible');

        //this.click('.abtl-step-conversion');

        this.click('.abtl-active .abtl-cutom-style-button');

        this.fillSelectors('#abtl-placeholder', {
            '.abtl-active .abtl-custom-style-classes': 'test-image dummy-class',
            '.abtl-active .abtl-custom-style-css': 'border: 10px solid red;'
        });

        this.click('.abtl-active .abtl-custom-style-close-button');

        test.assertMatch(this.getElementInfo('.test-image').attributes.class, /dummy-class/, 'Class modif. works');
        test.assertMatch(this.getElementInfo('.test-image').attributes.style, /border: 10px solid red;/, 'Style modif. works');
    });

    //add third test (upload a picture)
    casper.then(function() {
        this.clickLabel('+ Add new');

        this.fillSelectors('#abtl-placeholder', {
            '.abtl-active .abtl-test-title': 'third test picture upload'
        });

        //choose element to test
        this.click('.abtl-active .abtl-pick-element');
        this.click('.test-image-upload');

        this.click('.abtl-step-variation');

        this.evaluate(function() {
            abtl('.abtl-active .abtl-upload-or-url').val('upload').change();
        });

        test.assertVisible('.abtl-active .abtl-image-upload', 'Image upload field is visible');

        this.page.uploadFile('.abtl-active .abtl-image-upload','panda.jpg');

        this.wait(1000, function() {

            this.evaluate(function() {
                abtl('.abtl-active .abtl-image-upload').change();
            });

            test.assertVisible('.abtl-active .abtl-image-upload-preview', 'Upload image preview visible');
        });

    });

    //add custom conversion
    casper.then(function() {
        this.click('.abtl-active .abtl-step-conversion');
        this.click('.abtl-active .abtl-custom-conversion-button');
        this.clickLabel('#19 how to get better chroma key with Sony Vegas');

        test.assertTextExists('Conversion defined.', 'Conversion chosen.');
    });

    //save everything and see if it exists
    casper.then(function() {
        this.clickLabel('Save all');

        this.wait(4000, function(){
            this.clickLabel('Exit');
            this.wait(4000, function(){
                test.assertTextExists('first test title', 'First test saved.');
                test.assertTextExists('second test picture url', 'Second test saved');
                test.assertTextExists('third test picture upload', 'Third test saved.');
            });
        });
    });

    casper.run(function() {
        test.done();
    });



});