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
        }

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
        $(this).parent().find('span').each(function(){ $(this).css('visibility', 'visible'); });
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
    } while ($('#' + id).length)

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
};

function nextPos()
{
    var pos = getLeftPos() - getNavWidth();
    if (Math.abs(pos) > getListWidth() - getNavWidth())
        pos = (getListWidth() - getNavWidth() + em(1)) * -1;

    return  pos;
};

function prevPos()
{
    var pos = getLeftPos() + getNavWidth();
    if (pos > 0)
        pos = 0;

    return pos;
};

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