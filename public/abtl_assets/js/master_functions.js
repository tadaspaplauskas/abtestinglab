try {
    localStoreSupport = ('localStorage' in window && window['localStorage'] !== null);
} catch (e) {
    localStoreSupport = false;
}
//only for testing
//localStoreSupport = false;

function setLocal(name, data)
{
    jsonData = JSON.stringify(data);

    if (localStoreSupport)
    {
        return localStorage.setItem(name, jsonData);
    }
    else
    {
        var date = new Date();
        date.setTime(date.getTime()+(365 * 24 * 60 * 60)); //for a year
        var expires = "; expires=" + date.toGMTString();

        document.cookie = name + "=" + jsonData + expires + "; path=/";
        return true;
    }
}

function getLocal(name)
{
    if (localStoreSupport)
    {
        item = localStorage.getItem(name);
        if (item === null || item === undefined)
            return null;
        else
            return JSON.parse(localStorage.getItem(name));
    }
    else
    {
        name += '=';
        for (var ca = document.cookie.split(/;\s*/), i = ca.length - 1; i >= 0; i--)
        {    if (!ca[i].indexOf(name))
            {
                return JSON.parse(ca[i].replace(name, ''));
            }
        }
        return null;
    }
}

function customTrim(str) {
    if (str !== undefined && str !== null)
    {
        str = str.replace(/(\r\n|\n|\r)/gm,"").trim().replace(/(\s)/gm," ").replace("  ", " ");
        str = str.replace(' draggable="true" ondragstart="drag(event)"', '');
    }
    return str;
}

function allElements()
{
    var tags = 'img, tt, i, b, big, small, em, strong, dfn, code, samp, kbd, var, article, cite, abbr, acronym, sub, sup, span, bdo, address, div, a, object, p, h1, h2, h3, h4, h5, h6, pre, q, ins, del, dt, dd, li, label, option, legend, button, caption, td, th, title';
    return $("body").find(tags).filter(function() {
        return (directText($(this)).length > 0 || $(this).val() || $(this).attr('src'));
    });
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

function loadCSS(href)
{
    $('<link>')
        .appendTo('head')
        .attr({type : 'text/css', rel : 'stylesheet'})
        .attr('href', href);
}

function loadJS(href)
{
    $('<script>')
        .appendTo('head')
        .attr({type : 'text/javascript'})
        .attr('src', href);
}

function parentConversion(elem)
{
    if (elem.parents('a[href!=""]').length > 0)
        return true;
    else
        return false;
}