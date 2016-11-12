testUrl = 'http://abtestinglab.dev';
testUrl = 'https://abtestinglab.com';
//testUrl = 'http://staging.abtestinglab.com';

//cleanup - remove user ect.
casper.test.tearDown(function() {
    casper.echo('Cleanup...');
    //casper.capture('screen.jpg');
    casper.exit();
});

casper.test.begin('Delete website', 6, function suite(test) {
    casper.options.viewportSize = {width: 1920, height: 1080};
    //casper.options.verbose = true;
    //casper.options.logLevel ="debug";

    //cleanup
    casper.start(testUrl + "/login");

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

    casper.then(function() {
        this.clickLabel('For testing');
    });

    //delete testing website
    casper.then(function () {
        test.assertTextExists('Delete', 'Delete tests button exists');
        this.click('.btn-danger');
    });
    casper.then(function () {
        this.click('button[type=submit].btn-danger');
    });
    casper.then(function () {
        test.assertTextExists('Success', 'Success message displayed');
        test.assertTextDoesntExist('For testing', 'Testing website is gone');
    });


    casper.run(function() {
        test.done();
    });



});