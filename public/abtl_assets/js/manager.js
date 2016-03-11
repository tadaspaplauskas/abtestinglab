(function($, window, document) {
    //read manager token if its set
    var token = $.getLocal('token');

    if (token === null)
    {
        window.location = abtlBackUrl;
    }
    else
    {
        $(document).ready(function() {
        //do not track the manager
        $.setLocal('abtl_do_not_track', '1');

        //mutator too slow, lets make it an interval
        setInterval(function () {
            assignOriginalValues();
            applyActiveTest();

            //dragging functionality
            toggleDragging(true);
        }, 2000);

        assignOriginalValues();
        applyActiveTest();
        //dragging functionality
        toggleDragging(true);
        //cursor functionality
        toggleCursor('grab');

        /*************** PREPARING MANAGER **************/
        //assign original values to DOM objects
        assignOriginalValues();
        //taking care of stylesheets
        $.loadCSS(abtlUrl + '/abtl_assets/css/bootstrap/css/bootstrap.css');
        $.loadCSS(abtlUrl + '/abtl_assets/css/editor.css');

        //loading template
        $('body').append('<div id="abtl-placeholder" style="display:none">&nbsp;</div>');
        $('#abtl-placeholder').load(abtlUrl + '/abtl_assets/templates/editor_template.html', null, templateBindings);
        $('#abtl-placeholder').on('click', function (ev) {
            ev.stopPropagation();
        });

        //display body
        $('body').css('visibility', 'initial');

        var newChanges; //for logging new changes after save
        });
    }

    function assignOriginalValues()
    {
        $.allElements().not("#abtl-placeholder *").each(function()
        {
            //set original value if not assigned
            if (!$(this).data('original_value'))
            {
                if ($(this).prop('tagName') === 'IMG' && $(this).attr('src'))
                {
                    $(this).data('original_value', $(this).attr('src').customTrim());
                }
                else
                {
                    var value = '';

                    if ($(this).html().length > 0)
                    {
                        value = $(this).html().customTrim();
                    }
                    else if ($(this).attr('placeholder') !== undefined && $(this).attr('placeholder') !== null)
                    {
                        value = $(this).attr('placeholder', value);
                    }
                    else if ($(this).attr('value') !== undefined && $(this).attr('value') !== null)
                    {
                        value = $(this).attr('value', value);
                    }
                    $(this).data('original_value', value);
                }
                $(this).data('class', prepareClassNames($(this).attr('class')));
                $(this).data('style', prepareCSS($(this).attr('style')));
            }
        });
    }
    //picking a custom conversion element
    function pickConversionElement(btn, ev)
    {
        ev.stopPropagation();
        var currentObject = btn.parent();
        btn.prop('disabled', true);
        var message = currentObject.find('.abtl-picked-not-picked');
        btn.text('Click anywhere on the website');

        selection = $('body');
        toggleCursor('crosshair');

        selection.on('click', function (ev) {
            ev.preventDefault();
            var target = $(ev.target);

            if (!target.attr('href') && target.prop('tagName') !== 'INPUT' &&
                target.prop('tagName') !== 'BUTTON' &&
                !target.parents('a, button').length)
            {
                if (!confirm('This element does not appear to be optimal for click tracking. Are you sure you want to use it?'))
                return false;
            }

            var inputField = currentObject.find('.abtl-click-conversion-input');

            inputField.val($(ev.target).closest('a[href!=""]').attr('href'));

            setOneClass(target, 'abtl-picked-conversion-border');
            toggleCursor('grab');

            if (inputField.val().length > 0)
                btn.text('Conversion defined. Again?');
            else
                btn.text('This element cannot be tracked, please try another.');

            btn.prop('disabled', false);
            selection.off('click');
        });
    }

    //picking a custom conversion element
    function pickTestElement(btn, ev)
    {
        ev.stopPropagation();
        var selection = $('body');
        if (btn.text() === 'Click on any element or here to cancel')
        {
            selection.off('click');
            toggleCursor('grab');
            btn.text('Pick');
        }
        else
        {
            btn.text('Click on any element or here to cancel');
            currentObject = btn.up(2);

            toggleCursor('crosshair');

            selection.on('click', function (ev) {
                ev.preventDefault();
                if ($(ev.target).prop('draggable') === true)
                    {
                    var html = $(ev.target).html();
                    var tag = $(ev.target).prop('tagName');
                    var src = $(ev.target).attr('src');
                    var parentConv = $.parentConversion($(ev.target));

                    if (src !== undefined)
                        fillTest(src, tag, parentConv);
                    else
                        fillTest(html, tag, parentConv);

                    toggleCursor('grab');
                    btn.prop('disabled', false);
                    selection.off('click');
                }
            });
        }
    }

    function toggleCursor(cursor)
    {
        var selection = $('body');
        var cursors = 'abtl-cursor-grab abtl-cursor-grabbing abtl-cursor-crosshair';
        selection.removeClass(cursors);
        selection.addClass('abtl-cursor-' + cursor);
    }


    /****************** DRAGGING AND DROPPING ****************/

    function toggleDragging(on) {
        var abtlPanel = $("#abtl-placeholder *");
        var selection = $.allElements(true).not(abtlPanel);

        //make specific things draggable
        selection.each(function() {
            if (on) {
                $(this).attr("draggable", "true");
                //$(this).attr("ondragstart", "abtl.drag(event)");
                $(this).on('dragstart', drag);
            } else {
                $(this).attr("draggable", "false");
            }
        });
        //make everything NOT draggable, unless you are gonna do it anw
        if (on)
        {
            $('body *').not(selection).not(abtlPanel).each(function() {
                $(this).attr("draggable", "false");
            });
        }
    }

    function allowDrop(ev) {
        ev.preventDefault();
    }

    function drag(ev) {
        var elem = ev.originalEvent.dataTransfer;
        elem.setData('html', $(ev.target).html());
        elem.setData('tag', ev.target.tagName);
        elem.setData('parent_conversion', $.parentConversion($(ev.target)));

        if (ev.target.src)
        {
            elem.setData('src', ev.target.src);
            elem.setData('width', ev.target.clientWidth);
            elem.setData('height', ev.target.clientHeight);
        }
    }

    function drop(ev) {
        ev.preventDefault();
        var elem = ev.originalEvent.dataTransfer;
        var html = elem.getData('html').customTrim();
        var tag = elem.getData('tag').customTrim();
        var src = elem.getData('src');
        var width = elem.getData('width');
        var height = elem.getData('height');
        var parentConversion = (elem.getData('parent_conversion') === 'true');

        if (src.length)
            fillTest(src, tag, parentConversion, width, height);
        else
            fillTest(html, tag, parentConversion);
    }

    function fillTest(content, tag, parentConversion, width, height)
    {
        parentConversion = parentConversion || false;
        width = width || null;
        height = height || null;

        var before = $('.abtl-tab-content .abtl-active .abtl-before');
        var after = $('.abtl-tab-content .abtl-active .abtl-after');

        var identifier = before.find(".abtl-identifier-text");
        var testText = after.find(".abtl-test-text");

        var identifierImage = before.find(".abtl-identifier-image");
        var testImage = after.find(".abtl-test-image");

        if (testText.val().length > 0)
        {
            if (!confirmation('You have changes in this test that will be reset. Are you sure?'))
            {
                return false;
            }
        }
        if (content.length > 0)
        {
            if (content === testText.val())
            {
                alert('This element is selected');
                return false;
            }
            identifier.val(content.customTrim());
            testText.val(content.customTrim());
            testText.removeProp('disabled');
        }
        else
        {
            alert('Sorry, cannot identify the element.');
            return false;
        }
        //is element a link? if not, prepare to select conversion
        var conversionCheckbox = $('.abtl-active .abtl-default-conversion-checkbox input');

        //if link - check conversion checkbox by default
        if (tag === 'A' || parentConversion == true)
        {
            conversionCheckbox.prop('checked', true);
            conversionCheckbox.change();
            conversionCheckbox.prop('disabled', false);
        }
        else
        {
            conversionCheckbox.prop('checked', false);
            conversionCheckbox.change();
            conversionCheckbox.prop('disabled', true);
        }

        if (tag === 'IMG')
        {
            identifier.hide();
            testText.hide();

            identifierImage.attr('src', content);
            identifierImage.show();
            testImage.show();
            testImage.find('img').attr('src', '');
            testImage.find('input').val('');
            testImage.find('.abtl-upload-or-url').change();
        }
        else
        {
            identifier.show();
            testText.show();

            identifierImage.hide();
            testImage.hide();
        }
        changeIdentifierText();

        resetTests();
        testText.keyup();
        markChosenElements();
    }

    function setOneClass(target, styleClass)
    {
        $('.' + styleClass).removeClass(styleClass);
        target.addClass(styleClass);
    }

    /*********************** TEST MEAT ****************/
    function addTest(data)
    {
        data = data || false;

        id = makeID('abtl-test');
        //tab itself
        newTest = $('#abtl-test-template').clone(true).removeClass('abtl-hidden').appendTo("#abtl-tests-container");
        newTest.attr('id', id);
        //navigation
        newTestNav = $('#abtl-test-nav-template').clone(true).removeClass('abtl-hidden').insertAfter("#abtl-add-new-test");
        newTestNav.removeAttr('id');
        newTestNav.attr('data-tab', id);

        //style label according to test status
        var label = newTestNav;
        if (data.status !== undefined)
        {
            if (data.status === 'enabled')
            {
                label.addClass('abtl-enabled');
            }
            else if (data.status === 'disabled')
            {
                label.addClass('abtl-disabled');
            }
        }
        else
        {
            label.addClass('abtl-new');
        }

        //activate!
        $("#abtl-tests-container .abtl-active, #abtl-nav-tabs .abtl-active").removeClass('abtl-active');
        newTest.addClass('abtl-active');
        newTestNav.addClass('abtl-active');

        //enable dropping
        newTest.find('.abtl-before').on('drop', drop);
        newTest.find('.abtl-before').on('dragover', allowDrop);

        //assign values
        resetTests();
        if (data !== false)
        {
            newTest.data('id', data.id);
            testText = newTest.find('.abtl-test-text');
            testIdentifierText = newTest.find('.abtl-identifier-text');

            testText.prop('disabled', false);
            testText.val(data.test_variation);
            newTest.find('.abtl-click-conversion-input').val(data.conversion_element);
            testIdentifierText.val(data.test_element);

            //LOAD VALUES TO FIELDS
            if (data.element_type === 'image')
            {
                identifierImage = newTest.find(".abtl-identifier-image");
                testImage = newTest.find(".abtl-test-image");

                testText.hide();
                testIdentifierText.hide();

                identifierImage.attr('src', data.test_element);
                identifierImage.show();
                testImage.show();
                testImage.find('img').attr('src', data.test_variation);
                testImage.find('input').val('');
            }

            //STYLE RULES
            if (data.attributes.length > 0)
            {
                var newStyle = JSON.parse(data.attributes);
                newTest.find('.abtl-custom-style-classes').val(newStyle.class);
                newTest.find('.abtl-custom-style-css').val(newStyle.style);
            }

            //CONVERSION
            if (data.conversion_element !== null && data.conversion_element.length > 0)
            {
                if (data.conversion_type === 'click')
                {
                    newTest.find('.abtl-default-conversion-checkbox input').prop('checked', false);
                    newTest.find('.abtl-default-conversion-checkbox input').change();
                }
                else if (data.conversion_type === 'time')
                {
                    newTest.find('.abtl-conversion-type').val('time');
                }
                newTest.find('.abtl-time-conversion-input').val(data.conversion_element);
            }
            //by default use the same element if no conv data is specified
            else
            {
                newTest.find('.abtl-default-conversion-checkbox input').prop('checked', true);
                newTest.find('.abtl-default-conversion-checkbox input').change();
            }
            newTest.find('.abtl-conversion-type').change();

            //SET GOAL
            newTest.find('.abtl-conversion-goal').val(data.goal);

            newTestNav.find('.abtl-pick-test').text(data.title);

            changeIdentifierText();
            applyActiveTest();
        }
        reAdjust();
    }

    function chooseTest(elem)
    {
        liItem = elem.parent();

        //active already, rename
        if (liItem.hasClass('abtl-active'))
        {
            liItem.find('.abtl-test-title').val(liItem.find('a').text());
            liItem.find('.abtl-test-title').show();
            liItem.find('.abtl-pick-test').hide();
            liItem.find('.abtl-test-title').focus().select();
            liItem.find('span').each(function(){ $(this).css('visibility', 'hidden'); });
        }
        //activate
        else
        {
            $('.abtl-test-title').hide();
            $('.abtl-pick-test').show();
            $('#abtl-nav-tabs .abtl-active').removeClass('abtl-active');
            liItem.addClass('abtl-active');
        }

        elemID = elem.parent().attr('data-tab');
        $('#abtl-tests-container .abtl-active').removeClass('abtl-active');
        $('#' + elemID).addClass('abtl-active')
        //show first step
        .find('.abtl-step-element').click();

        resetTests();
        applyActiveTest();
    }

    function deleteTest(elem)
    {
        if (confirmation())
        {
            var elemID = elem.parent().attr('data-tab');

            //activate closest tab
            $('#' + elemID).nextOrPrev().addClass('abtl-active');
            //activate closest label
            elem.parent().nextOrPrev().addClass('abtl-active');

            //remove tab
            $('#' + elemID).remove();
            //remove label
            elem.parent().remove();
            resetTests();
            applyActiveTest();
            reAdjust();
        }
    }

    function applyActiveTest()
    {
        var testStyle = activeTestStyle();

        iterateThroughElements(fromField().val(), function (el) {
            if (el.prop('tagName') === 'IMG' && el.attr('src'))
            {
                el.attr('src', toField().val());
            }
            else
            {
                $.setVariation(el, toField().val());
            }

            if (testStyle.class.length > 0)
                el.attr('class', testStyle.class);
            else
                el.attr('class', el.data('class'));

            if (testStyle.style.length > 0)
                el.attr('style', testStyle.style);
            else
                el.attr('style', el.data('style'));
        });
        markChosenElements();
    }

    function markChosenElements()
    {
        iterateThroughElements(fromField().val(), function (el) {
                el.addClass('abtl-picked-test-border');
            });

        iterateThroughElements(conversionField().val(), function (el) {
                el.addClass('abtl-picked-conversion-border');
            });
    }

    function iterateThroughElements(field, fn)
    {
        if (field === undefined)
            return false;

        field = field.customTrim();
        var returnElem = null;

        if (field.length > 0)
        {
            $.allElements().not("#abtl-placeholder *").each(function()
            {
                if ($(this).data('original_value') === field || $(this).attr('href') === field)
                {
                    if (fn !== undefined)
                    {
                        fn($(this));
                    }
                    else
                    {
                        returnElem = $(this);
                        return;
                    }
                }
            });
            return returnElem;
        }
    }

    function resetTests()
    {
        //reset content
        $('.abtl-picked-test-border, .abtl-picked-conversion-border').each(function() {

            if ($(this).prop('tagName') === 'IMG' && $(this).attr('src'))
            {
                $(this).attr('src', $(this).data('original_value'));
            }
            else
            {
                $.setVariation($(this), $(this).data('original_value'));
            }

            $(this).removeClass('abtl-picked-test-border abtl-picked-conversion-border');
            $(this).attr('class', $(this).data('class'));
            $(this).attr('style', $(this).data('style'));
        });
    }

    function resetConversions()
    {
        //reset content
        $('.abtl-picked-conversion-border').each(function() {
            $(this).removeClass('abtl-picked-conversion-border');
        });
    }
    /*************************** LOADING, SAVING DATA ******************************/

    function loadTests()
    {
        //load tests from API
        apiCall('load', null, function(response) {
            //assign returned data to tests
            for(var i = 0; i < response.length; i++)
            {
                addTest(response[i]);
            }
            //only show when loaded
            $('#abtl-placeholder').show();
            reAdjust();

            //new changes
            newChanges = false;
        });
    }

    function saveTests()
    {
        var data = [];
        var success = true;
        $('#abtl-nav-tabs .abtl-tab-label[data-tab^="abtl-test-"]')
                .not('#abtl-test-template').each(function (){
            var tab = $('#' + $(this).data('tab'));

            //check if required info is provided: element, variation, conversion, goal
            if (tab.find('.abtl-identifier-text').val().length === 0
                || tab.find('.abtl-test-text').val().length === 0
                || tab.find('.abtl-conversion-goal').val() < 100 //is goal set?
                || (tab.find('.abtl-conversion-type').val() === 'click' && !tab.find('.abtl-default-conversion-checkbox input').prop('checked') && tab.find('.abtl-click-conversion-input').val().length === 0) //is click conv set?
                || (tab.find('.abtl-conversion-type').val() === 'time' && tab.find('.abtl-time-conversion-input').val().length === 0)) //or time goal?
            {
                alert('Test "' + $(this).find('.abtl-pick-test').text() + '" is not completed; you should select the element to be tested, then define the variation, conversion event, and finally the reach (>100).');
                success = false;
                return false;
            }
                data.push({
                id: tab.data('id'),
                tab: $(this).data('tab'),
                title: $(this).find('.abtl-pick-test').text(),
                from: tab.find('.abtl-identifier-text').val(),
                to: tab.find('.abtl-test-text').val(),
                conversion: {
                    type: tab.find('.abtl-conversion-type').val(),
                    conversion: (tab.find('.abtl-conversion-type').val() === 'click' ? tab.find('.abtl-click-conversion-input').val() : tab.find('.abtl-time-conversion-input').val())
                },
                goal: tab.find('.abtl-conversion-goal').val(),
                image_url: (tab.find('.abtl-test-image').css('display') === 'none' ? 0 : 1),
                attributes: {class: tab.find('.abtl-custom-style-classes').val(),
                            style: tab.find('.abtl-custom-style-css').val()}
            });
        });

        if (success)
        {
            //reverse array so that newer tests are in the front
            data.reverse();
            //sending to backend
            apiCall('save', data, function(response) {
                //assign returned id's to tabs
                for(var i = 0; i < response.length; i++)
                {
                    resp = response[i];
                    tab = $('#' + resp.tab);
                    tab.data('id', resp.id);
                }
                newChanges = false;
                alert('Saved successfully');
            });
        }
        return success;
    }

    //TEMPLATE MEAT HERE
    function templateBindings()
    {
        window.onbeforeunload = function()
        {
            return "Careful! If you have any unsaved changes, they will be lost.";
        }
        $('#abtl-placeholder').on('click', function() {
            window.onbeforeunload = null;
        });

        //help
        $('#abtl-save').prop('title', 'Save all changes and continue editing');
        $('#abtl-exit').prop('title', 'Exit editor now');
        $('#abtl-exit').click(exitManager);

        /*************** TEMPLATE FUNCTIONALITY ****************/

        $('textarea, input, select').change(function() { newChanges = true; });

        $('.abtl-steps li').click($(this).activateStep);

        $('.abtl-conversion-type').change($(this).changeConversionType);
        $('.abtl-identifier-text').change(function(){
            changeIdentifierText();
        });

        //custom style open
        $('.abtl-cutom-style-button').click($(this).openCustomStyle);
        //custom style close
        $('.abtl-custom-style-close-button').click($(this).closeCustomStyle);

        //image url changes
        $('.abtl-image-url').change($(this).changeImageUrl);

        //upload or url
        $('.abtl-upload-or-url').change($(this).changeImageSource);
        //initial after loading
        $('.abtl-upload-or-url').change();

        $('.abtl-test-text').keyup(applyActiveTest);

        $('.abtl-default-conversion-checkbox input').change($(this).changeDefaultConversationCheckbox);

        //save renamed test
        $('.abtl-test-title').blur($(this).changeTitle);
        //save title on enter key
        $('.abtl-test-title').keypress(function(e){
            if(e.which === 13)
            {
                $(this).blur();
            }
        });

        $(".abtl-custom-conversion-button").click(function (ev) {
            pickConversionElement($(this), ev);
        });

        $(".abtl-pick-element").click(function (ev) {
            pickTestElement($(this), ev);
        });

        $("#abtl-add-new-test").click(requestNewTest);

        $(".abtl-tab-label .abtl-pick-test").click(function (ev) {
            chooseTest($(this));
        });

        $('.abtl-delete-tab').click (function (ev) {
            deleteTest($(this));
        });

        $('.abtl-image-container').click ( function () {
            $(this).toggleClass('abtl-container-expanded');

        } );

        $(".abtl-image-upload").change($(this).previewImageUpload);

        $('#abtl-save').click(saveTests);

        $(window).on('resize',function(e){
            reAdjust();
        });

        $('.abtl-scroller-right').click(function() {
            $('.abtl-nav-list').animate({ left: nextPos() }, {always: function() {reAdjust();}});
        });

        $('.abtl-scroller-left').click(function() {
            $('.abtl-nav-list').animate({left: prevPos()}, {always: function() {reAdjust();}});
        });

        /* other stuff */
        loadTests();
    }

    function requestNewTest()
    {
        addTest();
        //show title input, encourage user to rename test
        $(".abtl-tab-label.abtl-active .abtl-pick-test").click();
        $(".abtl-tab-label.abtl-active .abtl-test-title").focus().select();
    }

    function changeIdentifierText()
    {
        var elem = $('.abtl-identifier-text');
        elem.each(function() {
            if ($(this).val().length > 0)
                    $(this).parent().find('.abtl-pick-element').text('Picked. Again?');
            else
                $(this).parent().find('.abtl-pick-element').text('Pick.');
        });

        var elem = $('.abtl-click-conversion-input');
        elem.each(function() {
            if ($(this).val().length > 0)
                    $(this).parent().find('.abtl-custom-conversion-button').text('Conversion defined. Again?');
            else
                $(this).parent().find('.abtl-pick-element').text('Pick an element to track conversions.');
        });

    }

    function apiCall(target, data, doneFn)
    {
        $.ajax({
            url: abtlUrl + '/api/' + target,
            cache: false,
            headers: {
                'token': token,
                'website-id': websiteID
            },
            method: 'POST',
            website_id: websiteID,
            async: true,
            dataType: 'json',
            data: {
                data: data,
                website_id: websiteID
            }
        })
        .done(function(response, status, request) {
            //refresh token
            var newToken = request.getResponseHeader('token');
            if (newToken !== null && newToken !== undefined)
            {
                $.setLocal('token', newToken);
                token = newToken;
            }
            //do whats needed with response
            doneFn(response);
        })
        .fail(function(response) {
            if (response.status === 400 || response.status === 401)
            {
                alert('Authentication failed, please open manager again');
                //window.location = abtlBackUrl;
            }
            else
            {
                alert('Request failed, please try again in a minute or two');
            }
        });
    }

    function makeID(pre)
    {
        var i = 1;
        var id = '';
        do
        {
            id = pre + '-' + i;
            i++;
        } while ($('#' + id).length);

        return id;
    }

    function isImageTest()
    {
        if ($('.abtl-tab-content .abtl-active .abtl-before .abtl-identifier-image').is(':visible')) {
            return true;
        }
        else
        {
            return false;
        }
    }

    function toField()
    {
        return $("#abtl-tests-container .abtl-active .abtl-test-text");
    }

    function fromField()
    {
        return $("#abtl-tests-container .abtl-active .abtl-identifier-text");
    }

    function conversionField()
    {
        return $("#abtl-tests-container .abtl-active .abtl-click-conversion-input");
    }

    function confirmation(text)
    {
        var text = text || 'Are you sure? This cannot be undone';
        return confirm(text);
    }

    function getCurrentElement()
    {
        return iterateThroughElements(fromField().val());
    }

    function prepareClassNames(str)
    {
        if (str !== undefined)
            return str.replace("abtl-picked-test-border", "").trim();
        else
            return '';
    }

    function prepareCSS(str)
    {
        if (str !== undefined)
            return str.replace("; ", ";\n")
                .replace(";", ";\n")
                .replace("\n\n", "\n")
                .replace('"', '\\"')
                .replace("'", "\'");
        else
            return '';
    }

    function activeTestStyle()
    {
        var current = $('#abtl-tests-container .abtl-active');
        return {class: current.find('.abtl-custom-style-classes').val(),
            style: current.find('.abtl-custom-style-css').val()};
    }

    /* make tab labels scrollable */
    function getListWidth()
    {
      var itemsWidth = 0;
      $('.abtl-nav-list li').each(function(){
        var itemWidth = $(this).outerWidth();
        itemsWidth+=itemWidth;
      });
      return itemsWidth;
    };

    function getNavWidth()
    {
        return $('.abtl-nav-wrapper').outerWidth();
    }

    function getLeftPos()
    {
      return $('.abtl-nav-list').position().left;
    }

    function nextPos()
    {
        var pos = getLeftPos() - getNavWidth();
        if (Math.abs(pos) > getListWidth() - getNavWidth())
            pos = (getListWidth() - getNavWidth() + em(1)) * -1;

        return  pos;
    }

    function prevPos()
    {
        var pos = getLeftPos() + getNavWidth();
        if (pos > 0)
            pos = 0;

        return pos;
    }

    function reAdjust()
    {
        if ((getLeftPos() * -1) + getNavWidth() < getListWidth()) {
            $('.abtl-scroller-right').show();
        }
        else {
            $('.abtl-scroller-right').hide();
        }

        if (getLeftPos() < -1) {
            $('.abtl-scroller-left').show();
        }
        else {
            $('.abtl-item').animate({left: 0});
            $('.abtl-scroller-left').hide();
        }
    }

    function em(input)
    {
        var emSize = parseFloat($("body").css("font-size"));
        return (emSize * input);
    }

    function exitManager()
    {
        if (newChanges && !confirm('There are new unsaved changes. Are you sure?'))
        {
            return false;
        }
        $.removeLocal('token');
        window.location = abtlBackUrl;

        return true;
    }

    /******************* VARIOUS FUNCTIONS, UTILITIES AND HELPERS ********************/
    $.fn.extend({
        /* go up the tree several times */
        up: function (level)
        {
            var elem = $(this);
            for(var i = 1; i <= level; i++)
            {
                elem = elem.parent();
            }
            return elem;
        },
        changeConversionType: function ()
        {
            var click = $(this).up(2).find('.abtl-click-conversion');
            var time = $(this).up(2).find('.abtl-time-conversion');

            if ($(this).val() === 'click')
            {
                click.show();
                time.hide();

            }
            else if ($(this).val() === 'time')
            {
                click.hide();
                time.show();
            }
        },
        openCustomStyle: function ()
        {
            var container = $(this).up(3).find('.abtl-custom-style-container');
            container.show();
            container.addClass('abtl-container-expanded');

            var classes = container.find('.abtl-custom-style-classes');
            var style = container.find('.abtl-custom-style-css');
            var element = getCurrentElement();

            if (element !== undefined)
            {
                if (classes.val().length === 0)
                {
                    classes.val(prepareClassNames(element.data('class')));
                }
                if (style.val().length === 0)
                {
                    style.val(prepareCSS(element.data('style')));
                }
            }
        },
        closeCustomStyle: function ()
            {
            var container = $(this).up(3);
            container.removeClass('abtl-container-expanded');
            container.hide();

            var classes = container.find('.abtl-custom-style-classes');
            var style = container.find('.abtl-custom-style-css');
            var element = getCurrentElement();

            if (classes.val() === prepareClassNames(element.data('class')))
            {
                classes.val('');
            }
            if (style.val() === prepareCSS(element.data('style')))
            {
                style.val('');
            }
            applyActiveTest();
        },
        previewImageUpload: function ()
        {
            var preview = $(this).up(4).find('.abtl-image-upload-preview');
            var file = $(this).parent().find('input[type=file]').prop('files')[0];

            if(file.size / 1024 > 2048) //2048kb limit
            {
                alert('Please select an image smaller than 2 mb.');
                return false;
            }

            var reader = new FileReader();

            reader.onloadend = function () {
                preview.attr('src', reader.result);
                toField().val(reader.result);
                applyActiveTest();
            };

            if (file) {
                reader.readAsDataURL(file);
            } else {
                preview.attr('src', '');
            }
        },
        nextOrPrev: function ()
        {
            var next = $(this).next();
            if (!next.length)
            {
                return $(this).prev();
            }
            else
            {
                return next;
            }
        },
        changeImageUrl: function ()
        {
            toField().val($(this).val());
            $(this).up(5).find('.abtl-image-upload-preview').attr('src', $(this).val());
            applyActiveTest();
        },
        changeImageSource: function ()
        {
            var area = $(this).up(2);

            if ($(this).val() === 'url')
            {
                area.find('.abtl-image-url').show();
                area.find('.abtl-image-upload').hide();
            }
            else
            {
                area.find('.abtl-image-url').hide();
                area.find('.abtl-image-upload').show();
            }
        },
        changeDefaultConversationCheckbox: function ()
        {
            if ($(this).is(':checked'))
            {
                $(this).up(3).find('.abtl-click-conversion-input').val('');
                resetConversions();

                $(this).up(3).find('.abtl-custom-conversion').hide();
                $('.abtl-test-tab.abtl-active .abtl-tests-window').removeClass('abtl-tests-window-smaller');
            } else
            {
                $(this).up(3).find('.abtl-custom-conversion').show();
            }
        },
        changeTitle: function ()
        {
            link = $(this).parent().find('.abtl-pick-test');
            $(this).parent().find('span').each(function(){ $(this).css('visibility', ''); });

            if ($(this).val().customTrim().length > 0)
                link.text($(this).val());

            link.show();
            $(this).hide();

            newChanges = true;
        },
        activateStep: function()
        {
            $('.abtl-active-step').removeClass('abtl-active-step');
            $('.' + this.className).addClass('abtl-active-step');

        }
    });
}(window.abtl, window, document));