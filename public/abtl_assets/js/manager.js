$(document).ready(function() {
    //read manager token if its set
    var token = getLocal('token');

    if (token === null)
    {
        window.location = abtlBackUrl;
    }
    else
    {
        //do not track the manager
        setLocal('abtl_do_not_track', '1');
        /*************** PREPARING MANAGER **************/
        //assign original values to DOM objects
        assignOriginalValues();
        //taking care of stylesheets
        loadCSS(abtlUrl + '/abtl_assets/css/editor.css');
        loadCSS(abtlUrl + '/abtl_assets/css/bootstrap/css/bootstrap.min.css');

        //loading template
        $('body').append('<div id="abtl-placeholder" style="display:none">&nbsp;</div>');
        $('#abtl-placeholder').load(abtlUrl + '/abtl_assets/templates/editor_template.html', null, templateBindings);
        $('#abtl-placeholder').on('click', function (ev) {
            ev.stopPropagation();
        });
        //dragging functionality
        toggleDragging(true);
        //cursor functionality
        toggleCursor('grab');

        //display body
        $('body').css('visibility', 'initial');
    }
});

function assignOriginalValues()
{
    allElements().not("#abtl-placeholder").each(function()
    {
        //set original value if not assigned
        if (!$(this).data('original_value'))
        {
            if ($(this).prop('tagName') === 'IMG')
            {
                $(this).data('original_value', customTrim($(this).attr('src')));
            }
            else
            {
                $(this).data('original_value', customTrim($(this).html()));
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
    var message = currentObject.find('.picked-not-picked');
    btn.text('Click anywhere on the website');

    selection = $('body');
    toggleCursor('crosshair');

    selection.on('click', function (ev) {
        ev.preventDefault();
        var target = $(ev.target);

        if (!target.attr('href') && target.prop('tagName') !== 'INPUT'
                && target.prop('tagName') !== 'BUTTON'
                && !target.parents('a, button').length)
        {
            if (!confirm('This element doesnt appear to be optimal for click tracking. Are you sure you want to use it?'))
            return false;
        }
        currentObject.find('.abtl-click-conversion-input').val($(ev.target).attr('href'));

        setOneClass(target, 'abtl-picked-conversion-border');
        toggleCursor('grab');
        btn.text('Conversion defined. Again?');
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
                var parentConv = parentConversion($(ev.target));

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
    var selection = allElements().not("#abtl-placeholder");

    //make specific things draggable
    selection.each(function() {
        if (on) {
            $(this).attr("draggable", "true");
            $(this).attr("ondragstart", "drag(event)");
        } else {
            $(this).attr("draggable", "false");
        }
    });
    //make everything NOT draggable, unless you are gonna do it anw
    if (on)
    {
        $('body').find('*').not(selection).each(function() {
            $(this).attr("draggable", "false");
        });
    }
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData('html', $(ev.target).html());
    ev.dataTransfer.setData('tag', ev.target.tagName);
    ev.dataTransfer.setData('parent_conversion', parentConversion($(ev.target)));

    if (ev.target.src)
    {
        ev.dataTransfer.setData('src', ev.target.src);
        ev.dataTransfer.setData('width', ev.target.clientWidth);
        ev.dataTransfer.setData('height', ev.target.clientHeight);
    }
}

function drop(ev) {
    ev.preventDefault();
    var html = customTrim(ev.dataTransfer.getData('html'));
    var tag = customTrim(ev.dataTransfer.getData('tag'));
    var src = ev.dataTransfer.getData('src');
    var width = ev.dataTransfer.getData('width');
    var height = ev.dataTransfer.getData('height');
    var parentConversion = (ev.dataTransfer.getData('parent_conversion') === 'true');

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

    var before = $('.tab-content .active .abtl-before');
    var after = $('.tab-content .active .abtl-after');

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
        identifier.val(customTrim(content));
        testText.val(customTrim(content));
        testText.removeProp('disabled');
    }
    else
    {
        alert('Sorry, cannot identify element.');
        return false;
    }
    //is element a link? if not, prepare to select conversion
    var conversionCheckbox = $('.active .abtl-default-conversion-checkbox input');

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
        testImage.find('.upload-or-url').change();
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
            label.addClass('enabled');
        }
        else if (data.status === 'disabled')
        {
            label.addClass('disabled');
        }
    }
    else
    {
        label.addClass('new');
    }

    //activate!
    $("#abtl-tests-container .active, #abtl-nav-tabs .active").removeClass('active');
    newTest.addClass('active');
    newTestNav.addClass('active');

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
            newTest.find('.custom-style-classes').val(newStyle.class);
            newTest.find('.custom-style-css').val(newStyle.style);
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
    if (liItem.hasClass('active'))
    {
        liItem.find('.test-title').val(liItem.find('a').text());
        liItem.find('.test-title').show();
        liItem.find('.abtl-pick-test').hide();
        liItem.find('.test-title').focus().select();
        liItem.find('span').each(function(){ $(this).css('visibility', 'hidden'); });
    }
    //activate
    else
    {
        $('.test-title').hide();
        $('.abtl-pick-test').show();
        $('#abtl-nav-tabs .active').removeClass('active');
        liItem.addClass('active');
    }

    elemID = elem.parent().attr('data-tab');
    $('#abtl-tests-container .active').removeClass('active');
    $('#' + elemID).addClass('active');

    resetTests();
    applyActiveTest();
}

function deleteTest(elem)
{
    if (confirmation())
    {
        var elemID = elem.parent().attr('data-tab');

        //activate closest tab
        $('#' + elemID).nextOrPrev().addClass('active');
        //activate closest label
        elem.parent().nextOrPrev().addClass('active');

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
            el.html(toField().val());
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
    field = customTrim(field);
    returnElem = null;

    if (field.length > 0)
    {
        allElements().not("#abtl-placeholder").each(function()
        {
            //check
            if ($(this).data('original_value') === field || $(this).attr('href') === field)
            {
                if (fn)
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
            $(this).html($(this).data('original_value'));
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
        if (tab.find('.abtl-identifier-text').val().length ===0
            || tab.find('.abtl-test-text').val().length === 0
            || tab.find('.abtl-conversion-goal').val() < 100 //is goal set?
            || (tab.find('.abtl-conversion-type').val() === 'click' && !tab.find('.abtl-default-conversion-checkbox input').prop('checked') && tab.find('.abtl-click-conversion-input').val().length === 0) //is click conv set?
            || (tab.find('.abtl-conversion-type').val() === 'time' && tab.find('.abtl-time-conversion-input').val().length === 0)) //or time goal?
        {
            alert('Test "' + $(this).find('.abtl-pick-test').text() + '" is not completed; you should select the tested element and then define the variation, conversion event.');
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
            attributes: {class: tab.find('.custom-style-classes').val(),
                        style: tab.find('.custom-style-css').val()}
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
            alert('Saved successfully');
        });
    }
    return success;
}

//TEMPLATE MEAT HERE
function templateBindings()
{
    //help
    $('#abtl-save').prop('title', 'Save all changes to website and continue editing');
    $('#abtl-exit').prop('title', 'Exit editor now');
    $('#abtl-exit').click(function(){
        removeLocal('token');
        window.location = abtlBackUrl;
    });

    /*************** TEMPLATE FUNCTIONALITY ****************/

    $('.abtl-steps li').click($(this).activateStep);

    $('.abtl-conversion-type').change($(this).changeConversionType);
    $('.abtl-identifier-text').change(function(){
        changeIdentifierText();
    });

    //custom style open
    $('.abtl-cutom-style-button').click($(this).openCustomStyle);
    //custom style close
    $('.custom-style-close-button').click($(this).closeCustomStyle);

    //image url changes
    $('.abtl-image-url').change($(this).changeImageUrl);

    //upload or url
    $('.upload-or-url').change($(this).changeImageSource);
    //initial after loading
    $('.upload-or-url').change();

    $('.abtl-test-text').keyup(applyActiveTest);

    $('.abtl-default-conversion-checkbox input').change($(this).changeDefaultConversationCheckbox);

    //save renamed test
    $('.test-title').blur($(this).changeTitle);
    //save title on enter key
    $('.test-title').keypress(function(e){
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

    loadTests();

    $(window).on('resize',function(e){
        reAdjust();
    });

    $('.scroller-right').click(function() {
        $('.nav-list').animate({ left: nextPos() }, {always: function() {reAdjust();}});
    });

    $('.scroller-left').click(function() {
        $('.nav-list').animate({left: prevPos()}, {always: function() {reAdjust();}});
    });
}

/******************* VARIOUS FUNCTIONS, UTILITIES AND HELPERS ********************/
jQuery.fn.extend({
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
        var container = $(this).up(3).find('.custom-style-container');
        container.show();
        container.addClass('abtl-container-expanded');

        var classes = container.find('.custom-style-classes');
        var style = container.find('.custom-style-css');
        var element = getCurrentElement();

        if (classes.val().length === 0)
        {
            classes.val(prepareClassNames(element.data('class')));
        }
        if (style.val().length === 0)
        {
            style.val(prepareCSS(element.data('style')));
        }
    },
    closeCustomStyle: function ()
        {
        var container = $(this).up(3);
        container.removeClass('abtl-container-expanded');
        container.hide();

        var classes = container.find('.custom-style-classes');
        var style = container.find('.custom-style-css');
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
        var preview = $(this).up(4).find('.image-upload-preview');
        var file = $(this).parent().find('input[type=file]').prop('files')[0];
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
        $(this).up(5).find('.image-upload-preview').attr('src', $(this).val());
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
            $('.abtl-test-tab.active .abtl-tests-window').removeClass('abtl-tests-window-smaller');
        } else
        {
            $(this).up(3).find('.abtl-custom-conversion').show();
        }
    },
    changeTitle: function ()
    {
        link = $(this).parent().find('.abtl-pick-test');
        $(this).parent().find('span').each(function(){ $(this).css('visibility', ''); });
        link.text($(this).val());
        link.show();
        $(this).hide();
    },
    activateStep: function()
    {
        $('.active-step').removeClass('active-step');
        $('.' + this.className).addClass('active-step');

    }
});

function requestNewTest()
{
    addTest();
    //show title input, encourage user to rename test
    $(".abtl-tab-label.active .abtl-pick-test").click();
    $(".abtl-tab-label.active .test-title").focus().select();
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
        setLocal('token', request.getResponseHeader('token'));
        token = request.getResponseHeader('token');
        //do whats needed with response
        doneFn(response);
    })
    .fail(function(response) {
        if (response.status === 400 || response.status === 401)
        {
            alert('Authentication failed, please open manager again');
            window.location = abtlBackUrl;
        }
        else
            alert('Request failed, please try again in a minute or two');
    });
}

function makeID(pre)
{
    i = 1;
    do
    {
        id = pre + '-' + i;
        i++;
    } while ($('#' + id).length);

    return id;
}

function isImageTest()
{
    if ($('.tab-content .active .abtl-before .abtl-identifier-image').is(':visible')) {
        return true;
    }
    else
    {
        return false;
    }
}

function toField()
{
    return $("#abtl-tests-container .active .abtl-test-text");
}

function fromField()
{
    return $("#abtl-tests-container .active .abtl-identifier-text");
}

function conversionField()
{
    return $("#abtl-tests-container .active .abtl-click-conversion-input");
}

function confirmation(text)
{
    text = text || 'Are you sure? This cannot be undone';
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
    var current = $('#abtl-tests-container .active');
    return {class: current.find('.custom-style-classes').val(),
        style: current.find('.custom-style-css').val()};
}

/* make tab labels scrollable */
function getListWidth()
{
  var itemsWidth = 0;
  $('.nav-list li').each(function(){
    var itemWidth = $(this).outerWidth();
    itemsWidth+=itemWidth;
  });
  return itemsWidth;
};

function getNavWidth()
{
    return $('.wrapper').outerWidth();
}

function getLeftPos()
{
  return $('.nav-list').position().left;
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
        $('.scroller-right').show();
    }
    else {
        $('.scroller-right').hide();
    }

    if (getLeftPos() < -1) {
        $('.scroller-left').show();
    }
    else {
        $('.item').animate({left: 0});
        $('.scroller-left').hide();
    }
}

function em(input)
{
    var emSize = parseFloat($("body").css("font-size"));
    return (emSize * input);
}