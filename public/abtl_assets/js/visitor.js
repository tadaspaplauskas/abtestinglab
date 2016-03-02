(function($, window, document) {
    var newVisitorCallSent = false;
    var testsVariationsStorage = 'abtl_t_v';
    var visitorStorage = 'abtl_vstr';

    //do not track the manager
    if ($.getLocal('abtl_do_not_track') !== '1')
    {
        //mutator
        if ('MutationObserver' in window)
        {
            var observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    applyTestsAndConversions(abtlData, $(mutation.target));
                });
            }).observe(document, {
                attributes: true,
                childList: true,
                characterData: true,
                subtree: true
            });
        }
        //ready
        $(document).ready(function() {
            applyTestsAndConversions(abtlData, null, true);
            $('body').css('visibility', 'visible');
        });
    }

    function applyTestsAndConversions(data, elem, ready)
    {
        var ready = ready || false;

        var testVariations = $.getLocal(testsVariationsStorage);
        if (testVariations === null)
        {
            var testVariations = {};
        }
        var newTestVariations = {};

        var tests = data ? data.tests : [];
        var conversions = data ? data.conversions : [];
        var finished = data ? data.finished : [];

        //looking for time conversions to apply, only when webpage is ready
        if (ready)
        {
            for(i = 0; i < conversions.length; i++)
            {
                var conversion = conversions[i];
                if (conversion.conversion_type === 'time')
                {
                    setTimeConversion(conversion.test_id, conversion.element);
                }
            }
        }

        if (elem !== undefined && elem !== null)
        {
            var elements = elem;
        }
        else
        {
            var elements = $.allElements();
        }

        elements.each(function()
        {
            //looking for click conversions to apply. Has to happen before tests
            for(i = 0; i < conversions.length; i++)
            {
                var conversion = conversions[i];

                if (conversion.conversion_type === 'click')
                {
                    if ($.compareElements($(this), conversion.element))
                    {
                        setClickConversion($(this), conversion.test_id);
                    }
                }
            }

            //looking for tests to apply
            for(i = 0; i < tests.length; i++)
            {
                var test = tests[i];

                //choosing a or b
                if (testVariations[test.id] !== undefined)
                {
                    variationChoice = testVariations[test.id];
                }
                else if (test.enabled == 1)
                {
                    variationChoice = randomChoice(test.variation_weight);
                    testVariations[test.id] = variationChoice;
                }
                else //if test is disabled and user has not seen it - skip
                {
                    continue;
                }
                //to omit removed tests from saving
                newTestVariations[test.id] = testVariations[test.id];

                if (variationChoice === 'b')
                {
                    var found = false;
                    if (test.element_type === 'image' && $.compareElements($(this), test.element))
                    {
                        $(this).attr('src', test.variation);
                        found = true;
                    }
                    else if ($.compareElements($(this), test.element))
                    {
                        $.setVariation($(this), test.variation);
                        found = true;
                    }
                    if(found && test.attributes)
                    {
                        if (test.attributes.style !== undefined)
                            $(this).attr('style', test.attributes.style);
                        if (test.attributes.class !== undefined)
                            $(this).attr('class', test.attributes.class);
                    }
                }
            }

            //looking for finished variations to apply
            for(i = 0; i < finished.length; i++)
            {
                var test = finished[i];

                //avoid if user already has predefined version for this test
                if (testVariations[test.id] !== undefined)
                    continue;

                if (test.element_type === 'image' && $.compareElements($(this), test.element))
                {
                    $(this).attr('src', test.variation);
                }
                else if ($.compareElements($(this), test.element))
                {
                    $.setVariation($(this), test.variation);
                }
                if(test.attributes)
                {
                    if (test.attributes.style !== undefined)
                        $(this).attr('style', test.attributes.style);
                    if (test.attributes.class !== undefined)
                        $(this).attr('class', test.attributes.class);
                }
            }
        });
        //log changes in tests and create new visitor if there is none yet
        if (newTestVariations.length > 0 && JSON.stringify($.getLocal(testsVariationsStorage)) != JSON.stringify(newTestVariations) || $.getLocal(visitorStorage) === null)
        {
            newVisitor(newTestVariations);
        }
        //saving variations for future use
        $.setLocal(testsVariationsStorage, newTestVariations);
    }

    function logVisit(tests)
    {
        var visitor = $.getLocal(visitorStorage);

        if (visitor !== null && visitor !== undefined && tests !==null && tests !== undefined)
        {
            //sending to backend
            var visitData = { visitor_id: visitor.visitor, tests: tests };
            $.ajax({
                url: abtlUrl + '/api/log_visit',
                method: 'POST',
                async: true,
                data: visitData});
        }
    }

    function saveConversion(testID)
    {
        var visitor = $.getLocal(visitorStorage);
        var variation = $.getLocal(testsVariationsStorage);

        if (visitor !== null && visitor !== undefined && variation !==null && variation !== undefined)
        {
            variation = variation[testID];
            //sending to backend
            conversionData = { test_id: testID, variation: variation, visitor_id: visitor.visitor };
            $.ajax({
                url: abtlUrl + '/api/save_conversion',
                method: 'POST',
                async: true,
                data: conversionData});
        }
    }

    function newVisitor(tests)
    {
        var visitor = $.getLocal(visitorStorage);
        if ((visitor === null || visitor === undefined) && newVisitorCallSent === false)
        {
            newVisitorCallSent = true;
            $.ajax({
                url: abtlUrl + '/api/new_visitor',
                method: 'POST',
                async: true,
                data: { website_id: websiteID }
            })
            .done(function(data){
                $.setLocal(visitorStorage, { visitor: data });
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

    function setTimeConversion(test, seconds)
    {
        setTimeout(function () { saveConversion(test); }, seconds * 1000);
    }

    function setClickConversion(obj, test)
    {
        obj.data('test_id', test);
        obj.off('.test-' + test).on('mousedown.test-' + test, function(ev){
            saveConversion(test);
            ev.preventDefault();
        });
    }
}(window.abtl, window, document));