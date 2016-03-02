String.prototype.customTrim = function () {
    var elem = this;
    if (elem !== undefined && elem !== null)
    {
        elem = elem.replace(/(\r\n|\n|\r)/gm,"").trim().replace(/(\s)/gm," ").replace("  ", " ").replace(' draggable="true" ondragstart="abtl.drag(event)"', '');
    }
    return elem;
};
abtl.extend({
    setVariation: function(elem, value)
    {
        if (elem.html().length > 0)
        {
            return elem.html(value);
        }
        else if (elem.attr('placeholder') !== undefined && elem.attr('placeholder') !== null)
        {
            return elem.attr('placeholder', value);
        }
        else if (elem.attr('value') !== undefined && elem.attr('value') !== null)
        {
            return elem.attr('value', value);
        }
    },
    compareElements: function(elem, value)
    {
        var href = elem.attr('href') || undefined;
        var src = elem.attr('src') || undefined;
        var placeholder = elem.attr('placeholder') || undefined;
        var elemVal = elem.attr('value') || undefined;
        var html = (elem.html() !== undefined) ? elem.html().customTrim() : undefined;

        return ((html !== undefined && html === value) ||
                    (href !== undefined && href === value) ||
                    (src !== undefined && src === value) ||
                    (placeholder !== undefined && placeholder === value) ||
                    (elemVal !== undefined && elemVal === value));
    },
    localStorageSupport: function ()
    {
        try {
            return ('localStorage' in window && window.localStorage !== null);
        } catch (e) {
            return false;
        }
    },
    setLocal: function (name, data)
    {
        jsonData = JSON.stringify(data);

        if (abtl.localStorageSupport())
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
    },
    getLocal: function (name)
    {
        if (abtl.localStorageSupport())
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
    },
    removeLocal: function (name)
    {
        if (abtl.localStorageSupport())
        {
            return localStorage.removeItem(name);
        }
        else
        {
            var expires = "";

            document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/";
            return true;
        }
    },
    parentConversion: function (elem)
    {
        if (elem.parents('a[href!=""]').length > 0)
            return true;
        else
            return false;
    },
    allElements: function (avoidEmpty)
    {
        var result;

        avoidEmpty = avoidEmpty || false;

        var tags = 'img, tt, i, b, big, small, em, strong, dfn, code, samp, kbd, var, article, cite, abbr, acronym, sub, sup, span, bdo, address, div, a, object, p, h1, h2, h3, h4, h5, h6, pre, q, ins, del, dt, dd, li, label, option, legend, button, caption, td, th, title';

        if (avoidEmpty)
        {
            result = abtl("body").find(tags).filter(function() {
                return (abtl.directText(abtl(this)).length > 0 || abtl(this).val() || abtl(this).attr('src') || abtl(this).attr('placeholder'));
            });
            //if (!abtl.elementsCacheWithEmpty)
            //{
                result = abtl("body").find(tags);

            //    abtl.elementsCacheWithEmpty = result;
            //}
            //else
            //{
            //    result = abtl.elementsCacheWithEmpty;
            //}
        }
        else
        {
            //if (!abtl.elementsCache)
            //{
                result = abtl("body").find(tags);
                //abtl.elementsCache = result;
            //}
            //else
            //{
            //    result = abtl.elementsCache;
            //}
        }
        return result;
    },
    directText: function (elem)
    {
        str = '';
        elem.contents().each(function() {
            if (this.nodeType === 3) {
                str += abtl(this).text();
            }
        });
        return str.trim();
    },
    loadCSS: function (href)
    {
        abtl('<link>')
            .appendTo('head')
            .attr({type : 'text/css', rel : 'stylesheet'})
            .attr('href', href);
    },
    loadJS: function (href)
    {
        abtl('<script>')
            .appendTo('head')
            .attr({type : 'text/javascript'})
            .attr('src', href);
    }
});