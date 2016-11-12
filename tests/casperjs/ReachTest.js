testUrl = 'http://blog.paplauskas.lt';
testUrl = 'http://abtestinglab.com/tests/blog.html';
repeat = 100;
//cleanup - remove user ect.
casper.test.tearDown(function() {
    //casper.capture('screen.jpg');
    casper.exit();
});

function loadBlog(count, limit)
{
    if (count > limit)
        return true;

    casper.thenOpen(testUrl).then(function() {
        casper.evaluate(function() {
            localStorage.clear();
            sessionStorage.clear();
        });
        casper.echo('Request nr. ' + count);
        casper.test.assertTitle("Tadas' blog", 'Page loaded');
    });

    casper.then(function()
    {
        loadBlog(count+1, limit);
    });
}

casper.test.begin('Do ' + repeat + ' requests', repeat, function suite(test) {
    casper.options.viewportSize = {width: 1920, height: 1080};
    //casper.options.verbose = true;
    //casper.options.logLevel ="debug";

    //anything
    casper.start()
    .then(function() {
        loadBlog(1, repeat);
    })
    .run(function() {
        test.done();
    });
});