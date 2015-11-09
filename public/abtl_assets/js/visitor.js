testsVariationsStorage = 'abtl_t_v';
visitorStorage = 'abtl_vstr';

$(document).ready(function() {
    //do not track the manager
    if (getLocal('abtl_do_not_track') !== '1')
        applyTestsAndConversions(abtlData);
});

function applyTestsAndConversions(data)
{
    var testVariations = getLocal(testsVariationsStorage);
    if (testVariations === null)
    {
        var testVariations = {};
    }
    var newTestVariations = {};

    var tests = data.tests;
    var conversions = data.conversions;

    //looking for time conversions to apply
    for(i = 0; i < conversions.length; i++)
    {
        var testID = conversions[i].test_id;
        var element = conversions[i].element;
        var type = conversions[i].conversion_type;
        if (type === 'time')
        {
            setTimeout(function() {
                saveConversion(testID);
            }, element * 1000);
        }
    }

    allElements().each(function()
    {
        //looking for click conversions to apply. Has to happen before tests
        for(i = 0; i < conversions.length; i++)
        {
            var testID = conversions[i].test_id;
            var element = conversions[i].element;
            var type = conversions[i].conversion_type;
            if (type === 'click')
            {   if (customTrim($(this).html()) === element
                    || customTrim($(this).attr('href')) === element
                    || customTrim($(this).attr('src')) === element)
                {
                    $(this).data('test_id', testID);
                    $(this).mousedown(function(ev){
                        saveConversion(testID);
                        ev.preventDefault();
                    });
                }
            }
        }

        //looking for tests to apply
        for(i = 0; i < tests.length; i++)
        {
            var testID = tests[i].id;
            var elementType = tests[i].element_type;
            var element = tests[i].element;
            var variation = tests[i].variation;
            var variationWeight = tests[i].variation_weight;
            var attributes = tests[i].attributes || null;

            //choosing a or b
            if (testVariations[testID] !== undefined)
            {
                variationChoice = testVariations[testID];
            }
            else
            {
                variationChoice = randomChoice(variationWeight);
                testVariations[testID] = variationChoice;
            }
            //to omit removed tests from saving
            newTestVariations[testID] = testVariations[testID];

            if (variationChoice === 'b')
            {
                var found = false;
                if (elementType === 'image' && customTrim($(this).attr('src')) === element)
                {
                    $(this).attr('src', variation);
                    found = true;
                }
                else if (customTrim($(this).html()) === element || customTrim($(this).attr('href')) === element)
                {
                    $(this).html(variation);
                    found = true;
                }
                if(found && attributes)
                {
                    if (attributes.style !== undefined)
                        $(this).attr('style', attributes.style);
                    if (attributes.class !== undefined)
                        $(this).attr('class', attributes.class);
                }
            }
        }
    });
    //log changes in tests and create new visitor if there is none yet
    if (JSON.stringify(getLocal(testsVariationsStorage)) != JSON.stringify(newTestVariations))
        newVisitor(newTestVariations);
    
    //saving variations for future use
    setLocal(testsVariationsStorage, newTestVariations);
    
}

function logVisit(tests)
{
    var visitor = getLocal(visitorStorage);

    if (visitor !== null && visitor !== undefined
            && tests !==null && tests !== undefined)
    {
        //sending to backend
        conversionData = { visitor_id: visitor['visitor'], tests: tests };
        $.ajax({
            url:"/api/log_visit",
            method: 'POST',
            async: true,
            data: conversionData});
    }

}

function saveConversion(testID)
{
    var visitor = getLocal(visitorStorage);
    var variation = getLocal(testsVariationsStorage);

    if (visitor !== null && visitor !== undefined
            && variation !==null && variation !== undefined)
    {
        variation = variation[testID];
        //sending to backend
        conversionData = { test_id: testID, variation: variation, visitor_id: visitor['visitor'] };
        $.ajax({
            url:"/api/save_conversion",
            method: 'POST',
            async: true,
            data: conversionData});
    }
}

function newVisitor(tests)
{
    var visitor = getLocal(visitorStorage);
    if (visitor === null || visitor === undefined)
    {
        $.ajax({
            url:"/api/new_visitor",
            method: 'POST',
            async: true,
            data: { website_id: websiteID }
        })
        .done(function(data){
            setLocal(visitorStorage, { visitor: data });
            logVisit(tests);
        });
    } else
    {
        logVisit(tests);
    }
}

function randomChoice(variationWeight)
{
    //random number 1-100
    var random = Math.floor(Math.random() * 100);

    if (random < variationWeight)
    {
        return 'b';
    }
    else
    {
        return 'a';
    }
}

