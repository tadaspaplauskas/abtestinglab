$(document).ready(function() {
    //read manager token if its set
    token = getLocal('token');

    if (token === null)
    {
        window.location = abtlBackUrl;
    }
    else
    {
        assignOriginalValues();

        loadCSS(abtlUrl + '/abtl_assets/css/editor.css');
        loadCSS(abtlUrl + '/abtl_assets/css/bootstrap/css/bootstrap.min.css');

        $('body').append('<div id="abtl-placeholder" style="display:none">&nbsp;</div>');
        $('#abtl-placeholder').load(abtlUrl + '/abtl_assets/templates/editor_template.html');

        //dragging functionality
        toggleDragging(true);
        toggleDragging(false, $('#abtl-placeholder').find('*'));

        $('#abtl-placeholder').on('click', function (ev) {
            ev.stopPropagation();
        });

        toggleCursor($('body'), 'grab');
    }
    //only hover action for those elements that cannot be selected
    $('[draggable=false]').mouseover(function (ev) {
        ev.stopPropagation();
        $(this).addClass('abtl-hover-not-allowed');
    }).mouseout(function (ev) {
        ev.stopPropagation();
        $(this).removeClass('abtl-hover-not-allowed');
    });
    
    //UI help to user
    /*$('[draggable=true]').mousedown(function (ev) {
        toggleCursor($('body'), 'grabbing');
    }).mouseup(function (ev) {
        $(this).mouseout();
    }).mouseout(function () { toggleCursor($('body'), 'grab'); });*/
});

//picking a custom conversion element
function pickConversionElement(btn, ev)
{
    currentObject = btn.parent();
    btn.prop('disabled', true);

    message = currentObject.find('.picked-not-picked');

    btn.text('Click anywhere on the website');

    ev.stopPropagation();

    //cancel out previous two lines or control because not() doesnt work

    selection = $('body');
    toggleCursor(selection, 'crosshair');

    selection.on('click', function (ev) {
        ev.preventDefault();
        var target = $(ev.target);
        
        if (!target.attr('href') && target.prop('tagName') !== 'INPUT' 
                && target.prop('tagName') !== 'BUTTON'
                && !target.parents('a, button').length)
        {
            console.log(target.parents('a, button').length);
            if (!confirm('This element doesnt appear to be optimal for click tracking. Are you sure you want to use it?'))
            return false;
        }
        currentObject.find('.conversion-input').val($(ev.target).attr('href'));

        setOneClass(target, 'abtl-picked-conversion-border');
        toggleCursor(selection, 'grab');
        btn.text('Conversion defined. Again?');
        btn.prop('disabled', false);
        selection.off('click');
    });
}

//picking a custom conversion element
function pickTestElement(btn, ev)
{
    ev.stopPropagation();
    selection = $('body');
    if (btn.text() === 'Click on any element or here to cancel')
    {
        selection.off('click');
        toggleCursor(selection, 'grab');
        btn.text('Pick');
    }
    else
    {
        btn.text('Click on any element or here to cancel');
        currentObject = btn.parent().parent();
        
        toggleCursor(selection, 'crosshair');

        selection.on('click', function (ev) {
            ev.preventDefault();

            var html = $(ev.target).html();
            var tag = $(ev.target).prop('tagName').toLowerCase();
            var src = $(ev.target).attr('src');
            var parentConv = parentConversion($(ev.target));

            if (src !== undefined)
                fillTest(src, tag, parentConv);
            else
                fillTest(html, tag, parentConv);

            toggleCursor(selection, 'grab');
            btn.prop('disabled', false);
            selection.off('click');
        });
    }
}

function toggleCursor(selection, cursor)
{
    //selection = selection.find('*');

    cursors = 'abtl-cursor-grab abtl-cursor-grabbing abtl-cursor-crosshair';

    selection.removeClass(cursors);
    selection.addClass('abtl-cursor-' + cursor);
    //$("#abtl-placeholder").find("*").removeClass(cursors);
}


/****************** DRAGGING AND DROPPING ****************/

function toggleDragging(on, selection) {
    selection = selection || allElements().not("#abtl-placeholder");
    
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
    
    //ev.dataTransfer.setData("className", ev.target.className);
    //ev.dataTransfer.setData("id", ev.target.id);
    //ev.dataTransfer.setData("content", customTrim($(ev.target).text()));
    ev.dataTransfer.setData('html', $(ev.target).html());
    //ev.dataTransfer.setData("name", ev.target.name);
    ev.dataTransfer.setData('tag', ev.target.tagName);
    ev.dataTransfer.setData('parent_conversion', parentConversion($(ev.target)));

    if (ev.target.src)
    {
        ev.dataTransfer.setData('src', ev.target.src);
        ev.dataTransfer.setData('width', ev.target.clientWidth);
        ev.dataTransfer.setData('height', ev.target.clientHeight);
    }
}

function drop(ev, handle) {
    ev.preventDefault();
    //var id = ev.dataTransfer.getData("id");
    //var className = ev.dataTransfer.getData("className");
    //var content = ev.dataTransfer.getData("content");
    var html = customTrim(ev.dataTransfer.getData('html'));
    var tag = customTrim(ev.dataTransfer.getData('tag'));
    var src = ev.dataTransfer.getData('src');
    var width = ev.dataTransfer.getData('width');
    var height = ev.dataTransfer.getData('height');
    var parentConversion = ev.dataTransfer.getData('parent_conversion');
    
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
    
    var identifier = before.find(".abtl-identifier");
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
    resetTests();
    testText.keyup();
    markChosenElements();

    //is element a link? if not, prepare to select conversion
    conversionCheckbox = $('.tab-content .active .abtl-default-conversion-checkbox input');

    //if link - check conversion checkbox by default
    if (tag.toLowerCase() === 'a' || parentConversion === 'true')
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

    if (tag.toLowerCase() === 'img')
    {
        identifier.hide();
        testText.hide();

        identifierImage.attr('src', content);
        identifierImage.show();
        testImage.show();
        testImage.find('img').attr('src', '');
        testImage.find('input').val('');
        testImage.find('.imageWidth').val(width);
        testImage.find('.imageHeight').val(height);
        testImage.find('.upload-or-url').change();
        testImage.find('.initial-or-new-size').change();
    }
    else
    {
        identifier.show();
        testText.show();

        identifierImage.hide();
        testImage.hide();
    }
    $('.abtl-identifier').change();
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
        testIdentifier = newTest.find('.abtl-identifier');
        
        testText.prop('disabled', false);
        testText.val(data.test_variation);
        newTest.find('.conversion-input').val(data.conversion_element);
        testIdentifier.val(data.test_element);
        
        //load values to fields
        if (data.element_type === 'image')
        {
            identifierImage = newTest.find(".abtl-identifier-image");
            testImage = newTest.find(".abtl-test-image");

            testText.hide();
            testIdentifier.hide();

            identifierImage.attr('src', data.test_element);
            identifierImage.show();
            testImage.show();
            testImage.find('img').attr('src', data.test_variation);
            testImage.find('input').val('');
        }
        
        if (data.conversion_element.length > 0)
        {
            newTest.find('.abtl-default-conversion-checkbox input').prop('checked', false);
            newTest.find('.abtl-default-conversion-checkbox input').change();
        }
        
        if (data.attributes.length > 0)
        {
            var newStyle = JSON.parse(data.attributes);
            newTest.find('.custom-style-classes').val(newStyle.class);
            newTest.find('.custom-style-css').val(newStyle.style);
        }
        
        newTestNav.find('.abtl-pick-test').text(data.title);

        $('.abtl-identifier').change();
        applyActiveTest();
    }
}

function chooseTest(elem)
{
    liItem = elem.parent();

    //active already, rename
    if (liItem.hasClass('active'))
    {
            liItem.find('.test-title').show();
            liItem.find('.abtl-pick-test').hide();
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
        elemID = elem.parent().attr('data-tab');

        //activate closest tab
        nextOrPrev($('#' + elemID)).addClass('active');
        //activate closest label
        nextOrPrev(elem.parent()).addClass('active');

        //remove tab
        $('#' + elemID).remove();
        //remove label
        elem.parent().remove();
        resetTests();
        applyActiveTest();
    }
}

function applyActiveTest()
{
    var testStyle = activeTestStyle();
    
    iterateThroughElements(fromField().val(), function (el) {
        if (el.prop('tagName').toLowerCase() === 'img' && el.attr('src'))
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

        if ($(this).prop('tagName').toLowerCase() === 'img' && $(this).attr('src'))
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

    //reset preview image

}

function resetConversions()
{
    //reset content
    $('.abtl-picked-conversion-border').each(function() {
        $(this).removeClass('abtl-picked-conversion-border');
    });
}

function assignOriginalValues()
{
    allElements().not("#abtl-placeholder").each(function()
    {
        //set original value if not assigned
        if (!$(this).data('original_value'))
        {
            if ($(this).prop('tagName').toLowerCase() === 'img')
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
    });
}

function saveTests()
{
    var data = [];
    $('#abtl-nav-tabs .abtl-tab-label[data-tab^="abtl-test-"]')
            .not('#abtl-test-template').each(function (){
        var tab = $('#' + $(this).data('tab'));
        var size = tab.find('.initial-or-new-size');
        data.push({
            id: tab.data('id'),
            tab: $(this).data('tab'),
            title: $(this).find('.abtl-pick-test').text(),
            from: tab.find('.abtl-identifier').val(),
            to: tab.find('.abtl-test-text').val(),
            conversion: tab.find('.conversion-input').val(),
            image_url: (tab.find('.abtl-test-image').css('display') === 'none' ? 0 : 1),
            attributes: {class: tab.find('.custom-style-classes').val(),
                        style: tab.find('.custom-style-css').val()}
        });
    });
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

function publishTests()
{
    saveTests();
    window.location = abtlUrl + '/tests/publish/' + websiteID;
}

/*************************** UTILITIES AND HELPERS *******************/

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
            window.location = abtlUrl + '/website/show/' + websiteID;
        }
        else
            alert('Request failed, please try again in a minute or two');
    });
}

function nextOrPrev(elem)
{
    next = elem.next();
    if (!next.length)
    {
        return elem.prev();
    }
    else
    {
        return next;
    }
}

function makeID(pre)
{
    i = 1;
    do
    {
        id = pre + '-' + i;
        i++;
    } while ($('#' + id).length)

    return id;
}

function customTrim(str)
{
    str = str.replace(/(\r\n|\n|\r)/gm,"").trim().replace(/(\s)/gm," ").replace("  ", " ");
    str = str.replace(' draggable="true" ondragstart="drag(event)"', '');
    return str;
}

function allElements()
{
    var elementsToDrag = 'img, tt, i, b, big, small, em, strong, dfn, code, samp, kbd, var, article, cite, abbr, acronym, sub, sup, span, bdo, address, div, a, object, p, h1, h2, h3, h4, h5, h6, pre, q, ins, del, dt, dd, li, label, option, legend, button, caption, td, th, title';
    return $("body").find(elementsToDrag).filter(function() {
        return (directText($(this)).length > 0 || $(this).val() || $(this).attr('src'));
    });
}

/*function allConversionElements()
{
    var elementsToDrag = 'img, tt, i, b, big, small, em, strong, dfn, code, samp, kbd, var, article, cite, abbr, acronym, sub, sup, span, bdo, address, div, a, object, p, h1, h2, h3, h4, h5, h6, pre, q, ins, del, dt, dd, li, label, option, legend, button, caption, td, th, title';
    return $("body").find(elementsToDrag).filter(function() {
        return ($(this).click !== undefined || $(this).attr('href') || $(this).prop('tagName') === 'INPUT' || $(this).prop('tagName') === 'BUTTON');
    });
}*/

function previewImageUpload (elem) {
    preview = elem.parent().parent().parent().parent().find('.image-upload-preview');
    file = elem.parent().find('input[type=file]').prop('files')[0];
    reader = new FileReader();

    reader.onloadend = function () {
        preview.attr('src', reader.result);
        toField().val(reader.result);
        applyActiveTest();
    }

    if (file) {
        reader.readAsDataURL(file);
    } else {
        preview.attr('src', '');
    }
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
    return $("#abtl-tests-container .active .abtl-identifier");
}

function conversionField()
{
    return $("#abtl-tests-container .active .conversion-input");
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

function directText(elem)
{
    str = '';
    elem.contents().each(function() {
        if (this.nodeType === 3) {
            str += $(this).text();
        }
    });
    return str.trim();
}