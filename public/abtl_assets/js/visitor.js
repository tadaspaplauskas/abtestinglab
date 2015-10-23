testsVariationsStorage = 'abtl_t_v';
visitorStorage = 'abtl_vstr';

$(document).ready(function() {
    websiteID = 1; //add ID here
    applyTestsAndConversions(abtlData);
});

function applyTestsAndConversions(data)
{
    testVariations = getLocal(testsVariationsStorage);
    if (testVariations === null)
    {
        testVariations = {};
    }
    
    tests = data.tests;
    conversions = data.conversions;

    allElements().each(function()
    {
        //looking for conversions to apply. Has to happen before tests
        for(i = 0; i < conversions.length; i++)
        {
            testID = conversions[i].test_id;
            element = conversions[i].element;
            
            if (customTrim($(this).html()) === element
                || customTrim($(this).attr('href')) === element
                || customTrim($(this).attr('src')) === element)
            {
                $(this).data('test_id', testID);
                $(this).mousedown(function(ev){
                    saveConversion($(this));
                    ev.preventDefault();
                });
            }
        }
        
        //looking for tests to apply
        for(i = 0; i < tests.length; i++)
        {
            testID = tests[i].id;
            elementType = tests[i].element_type;
            element = tests[i].element;
            variation = tests[i].variation;
            variationWeight = tests[i].variation_weight;

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

            if (variationChoice === 'b')
            {
                if (elementType === 'image' && customTrim($(this).attr('src')) === element)
                {
                    $(this).attr('src', variation);
                }
                else if (customTrim($(this).html()) === element || customTrim($(this).attr('href')) === element)
                {                    
                    $(this).html(variation);                    
                }
            }
        }
    });
    //saving variations for future use
    setLocal(testsVariationsStorage, testVariations);
    newVisitor();
}


function saveConversion(elem)
{
    testID = elem.data('test_id');
    visitor = getLocal(visitorStorage);
    variation = getLocal(testsVariationsStorage)
    
    if (visitor !== null && visitor !== undefined
            && variation !==null && variation != undefined)
    {
        variation = variation[testID];
        visitor = visitor['visitor'];
        //sending to backend
        conversionData = { test_id: testID, variation: variation, visitor_id: visitor };
        $.ajax({
            url:"/api/save_conversion",
            method: 'POST',
            async: true,
            data: conversionData});
    }
}

function newVisitor()
{
    visitor = getLocal(visitorStorage);
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
        });
    }
}

function randomChoice(variationWeight)
{
    //random number 1-100
    random = Math.floor(Math.random() * 100);

    if (random < variationWeight)
    {
        return 'b';
    }
    else
    {
        return 'a';
    }
}

