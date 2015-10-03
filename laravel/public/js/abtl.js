$(document).ready(function() {
    assignOriginalValues();
    
    $("body").append('<div id="abtl-placeholder">&nbsp;</div>');
    
    $("#abtl-placeholder").loadTemplate("js/templates/changes.html",
        {
            amount: 0
        });
        
    //dragging functionality
    toggleDragging(true);
    toggleDragging(false, $("#abtl-placeholder").find("*"));
    
    $('#abtl-placeholder').on('click', function (ev) {
        ev.stopPropagation();
    });
    
    toggleCursor($("body"), 'grab');
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
    
    selection = $("body");
    toggleCursor(selection, 'crosshair');
    
    selection.on('click', function (ev) {
        ev.preventDefault();
        
        /*var conversion = {id : ev.target.id,
                        class : ev.target.className,
                        tag : ev.target.className,
                        html : customTrim($(ev.target).html())};
        currentObject.find('.conversion-input').val(JSON.stringify(conversion));*/
        
        if ($(ev.target).attr('href'))
            currentObject.find('.conversion-input').val($(ev.target).attr('href'));
        else
            currentObject.find('.conversion-input').val($(ev.target).text());
        
        setOneClass(ev.target, 'abtl-picked-conversion-border');
        toggleCursor(selection, 'grab');        
        btn.text('Conversion picked. Again?');
        btn.prop('disabled', false);
        selection.off('click');
    });
}

//picking a custom conversion element
function pickTestElement(btn, ev)
{
    currentObject = btn.parent().parent();
    
    btn.text("Click anywhere on the website");
    btn.prop('disabled', true);
    ev.stopPropagation();    
    
    selection = $("body");
    toggleCursor(selection, 'crosshair');
    
    selection.on('click', function (ev) {
        ev.preventDefault();
        
        prepareTest(customTrim($(ev.target).html()));
    
        toggleCursor(selection, 'grab');        
        btn.text('Picked. Again?');
        btn.prop('disabled', false);
        selection.off('click');
    });
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
    
    selection.each(function() {
        if (on) {
            $(this).attr("draggable", "true");
            $(this).attr("ondragstart", "drag(event)");
        } else {            
            $(this).attr("ondragstart", "");
            $(this).attr("draggable", "false");
        }
    });
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {    
    //ev.dataTransfer.setData("className", ev.target.className);
    //ev.dataTransfer.setData("id", ev.target.id);
    //ev.dataTransfer.setData("content", customTrim($(ev.target).text()));
    ev.dataTransfer.setData("html", $(ev.target).html());
    //ev.dataTransfer.setData("name", ev.target.name);
    ev.dataTransfer.setData("tag", ev.target.tagName);    
    if (ev.target.src)
        ev.dataTransfer.setData("src", ev.target.src);
}

function drop(ev, handle) {
    ev.preventDefault();
    //var id = ev.dataTransfer.getData("id");
    //var className = ev.dataTransfer.getData("className");
    //var content = ev.dataTransfer.getData("content");
    var html = customTrim(ev.dataTransfer.getData("html"));
    var tag = customTrim(ev.dataTransfer.getData("tag"));
    var src = ev.dataTransfer.getData("src");
    
    if (src.length)
        prepareTest(src, tag);
    else
        prepareTest(html, tag);
}

function prepareTest(content, tag)
{
    identifier = $('.tab-content .active .abtl-before').find(".abtl-identifier");
    testText = $('.tab-content .active .abtl-after').find(".abtl-test-text");
    
    identifierImage = $('.tab-content .active .abtl-before').find(".abtl-identifier-image");
    testImage = $('.tab-content .active .abtl-after').find(".abtl-test-image");

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
        identifier.val(content);
        testText.val(content);
        testText.removeProp('disabled');
    }
    else
    {
        alert('sorry, cannot identify element, results might be bad');
    }
    resetTests();
    testText.keyup();
    markChosenElements();

    //is element a link? if not, prepare to select conversion
    conversionCheckbox = $('.tab-content .active .abtl-default-conversion-checkbox input');

    //conditionals to take care of businesss
    if (tag.toLowerCase() === 'a')
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
   }
    else
    {
        identifier.show();
        testText.show();

        identifierImage.hide();
        testImage.hide();
    }
}

function setOneClass(target, styleClass)
{
    $('.' + styleClass).removeClass(styleClass);
    $(target).addClass(styleClass);
}


/*********************** TEST MEAT ****************/
function addNewTest(data)
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
    if (data)
    {
        //load values to fields
        newTest.data('id', data.id);
        newTest.find('.abtl-identifier').val(data.test_element);
        newTest.find('.abtl-test-text').val(data.test_variation);
        newTest.find('.abtl-identifier').val(data.test_element);
        newTest.find('.conversion-input').val(data.conversion_element);
        if (data.conversion_element.length > 0)
        {
            newTest.find('.abtl-default-conversion-checkbox input').prop('checked', false);
            newTest.find('.abtl-default-conversion-checkbox input').change();
        }
        newTestNav.find('.abtl-pick-test').text(data.title);        
        
        applyTests();
        markChosenElements();
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
    markChosenElements();
    applyTests();
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
        applyTests();
    }
}

function applyTests()
{
    iterateThroughElements(fromField().val(), function (el) { 
        if (el.prop('tagName').toLowerCase() === 'img' && el.attr('src'))
        {
            el.attr('src', toField().val());
        }
        else
        {
            el.html(toField().val());
        }
    });
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

    if (field.length > 0)
    {
        allElements().not("#abtl-placeholder").each(function()
        {
            //check
            if ($(this).data('original_value') === field || $(this).attr('href') === field)
            {
                fn($(this));
            }
        });
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
            
            //$(this).data('original_html', $(this).html());
        }
    });
}


/*************************** LOADING, SAVING DATA ******************************/

function loadTests()
{    
    //load tests from API
    $.post('/api/load', { website_id: $('#abtl-control').data('website')}, function(response) {
        //assign returned data to tests
        console.log(response);
        for(var i = 0; i < response.length; i++)
        {
            addNewTest(response[i]);
        }
    }, "json");
}


function saveTests()
{
    data = [];
    $('#abtl-nav-tabs .abtl-tab-label[data-tab^="abtl-test-"]').not('#abtl-test-template').each(function (){
        tab = $('#' + $(this).data('tab'));
        data.push({
            id: tab.data('id'),
            tab: $(this).data('tab'),
            title: $(this).find('.abtl-pick-test').text(),
            from: tab.find('.abtl-identifier').val(),
            to: tab.find('.abtl-test-text').val(),
            conversion: tab.find('.conversion-input').val()
        });
    });
    
    //sending to backend
    $.post('/api/save', { website_id: $('#abtl-control').data('website'),
                        data: data }, function(response) {
        //assign returned id's to tabs
        for(var i = 0; i < response.length; i++)
        {
            resp = response[i];
            tab = $('#' + resp.tab);
            tab.data('id', resp.id);
        }
        alert('Saved successfully');
    }, "json");
}


function publishTests()
{
    //just regenerate JS file - all there is to it
    data = [];
    $('#abtl-nav-tabs .abtl-tab-label[data-tab^="abtl-test-"]').not('#abtl-test-template').each(function (){
        tab = $('#' + $(this).data('tab'));
        data.push({
            id: tab.data('id'),
            tab: $(this).data('tab'),
            title: $(this).find('.abtl-pick-test').text(),
            from: tab.find('.abtl-identifier').val(),
            to: tab.find('.abtl-test-text').val(),
            conversion: tab.find('.conversion-input').val()
        });
    });
    
    //sending to backend
    $.post('/api/save', { website_id: $('#abtl-control').data('website'),
                        data: data }, function(response) {
        //assign returned id's to tabs
        for(var i = 0; i < response.length; i++)
        {
            resp = response[i];
            tab = $('#' + resp.tab);
            tab.data('id', resp.id);
            console.log(tab.data('id'));
        }        
    }, "json");
}

/*************************** UTILITIES AND HELPERS *******************/

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
        i++
    } while ($('#' + id).length)
    
    return id;
}

function customTrim(str) {
    str = str.replace(/(\r\n|\n|\r)/gm,"").trim().replace(/(\s)/gm," ").replace("  ", " ");
    str = str.replace(' draggable="true" ondragstart="drag(event)"', '');
    return str;
}

function allElements()
{
    elementsToDrag = 'img, tt, i, b, big, small, em, strong, dfn, code, samp, kbd, var, article, cite, abbr, acronym, sub, sup, span, bdo, address, div, a, object, p, h1, h2, h3, h4, h5, h6, pre, q, ins, del, dt, dd, li, label, option, legend, button, caption, td, th, title';
    return $("body").find(elementsToDrag);
}


function previewImageUpload (elem) {
    preview = elem.parent().parent().find('.image-upload-preview');
    file = elem.parent().find('input[type=file]').prop('files')[0];
    reader = new FileReader();

    reader.onloadend = function () {
        preview.attr('src', reader.result);
        toField().val(reader.result);
        applyTests();
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