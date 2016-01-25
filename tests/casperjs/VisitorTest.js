testUrl = 'http://abtestinglab.dev';
url = testUrl + '/tests/blog.html';


//cleanup - remove user ect.
casper.test.tearDown(function() {
    //casper.capture('screen.jpg');
    casper.exit();
});


casper.test.begin('Test visitors side of things (js + api)', 2, function suite(test) {
    casper.options.viewportSize = {width: 1920, height: 1080};
    //casper.options.verbose = true;
    //casper.options.logLevel ="debug";

    //anything
    casper.start();

    casper.echo('THIS HAS TO BE RUN AFTER CreatingTest.js');

    casper.thenOpen(testUrl + '/auth/logout');

    casper.then(function() {
        casper.evaluate(function() {
            localStorage.clear();
            sessionStorage.clear();
        });
    });

    casper.thenOpen(url).then(function (){
        casper.test.assertTitle("Tadas' blog", 'Test page loaded');
        casper.echo('Clicking first conversion');
    });

    casper.thenEvaluate(function() {
        abtl('a').filter(function(index) { return abtl(this).text() == "#19 how to get better chroma key with Sony Vegas"; }).mousedown();
    });

    casper.thenOpen(url);

    casper.then(function () {
        casper.echo('Clicking second conversion');
    });

    casper.thenEvaluate(function() {
        abtl('.test-image').mousedown();
    });

    casper.thenOpen(url);

    casper.then(function() {
        casper.echo('Waiting for the third conversion to happen');

        //one of the conversions is time, so gotta wait for it to register
        this.wait(20000, function() {
            casper.echo('Wait is over, continue');

            casper.thenOpen(testUrl + '/test_conversions_check', function () {
                test.assertTextExists('pass', 'Conversion and views count is correct');
            });
        });
    });

    casper.run(function() {
        test.done();
    });
});