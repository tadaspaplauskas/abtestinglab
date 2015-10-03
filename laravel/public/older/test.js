
function GetURLParameter(sParam) {
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}

function customTrim(str) {
    return str.replace(/(\r\n|\n|\r)/gm,"").trim().replace(/(\s)/gm," ").replace("  ", " ");
}

function allowDrop(ev) {
    ev.preventDefault();
}

function drag(ev) {
    ev.dataTransfer.setData("className", ev.target.className);
    ev.dataTransfer.setData("id", ev.target.id);
    
    ev.dataTransfer.setData("content", customTrim($(ev.target).text()));
    ev.dataTransfer.setData("name", ev.target.name);
    ev.dataTransfer.setData("tag", ev.target.tagName);
}

function drop(ev, handle) {
    ev.preventDefault();
    var className = ev.dataTransfer.getData("className");
    var id = ev.dataTransfer.getData("id");
    var content = ev.dataTransfer.getData("content");
    var name = ev.dataTransfer.getData("name");
    var tag = ev.dataTransfer.getData("tag");
    
    var byWhat = $(handle).find(".abtl-identifyBy").val();
    
    $(handle).find(".abtl-action").val('');
    $(handle).find(".abtl-action").change();
    
    if (byWhat === 'smart') {
        if (id && id != 'undefined') {
            $(handle).find(".abtl-identifier").val("#" + id);
        } else if (name && name != 'undefined'){
            $(handle).find(".abtl-identifier").val("[name=\"" + name + "\"]");
        } else if (content.length > 0){            
            $(handle).find(".abtl-identifier").val(content);
        } else if (className && className != 'undefined'){
            $(handle).find(".abtl-identifier").val("." + className);
        } else {
            $(handle).find(".abtl-identifier").val("" + tag + "");
        }
    } else if (byWhat === 'class' && className && className != 'undefined') {
        $(handle).find(".abtl-identifier").val("" + className);
    } else if (byWhat === 'id' && id && id != 'undefined') {
        $(handle).find(".abtl-identifier").val("" + id);
    } else if (byWhat === 'name' && name && name != 'undefined') {
        $(handle).find(".abtl-identifier").val("name=" + name + "");
    } else if (byWhat === 'tag') {
        $(handle).find(".abtl-identifier").val("" + tag + "");
    } else if (byWhat === 'content') {
        $(handle).find(".abtl-identifier").val(content);
    }
    $(handle).find(".abtl-identifier").change();
}

$.fn.isOnScreen = function(x, y){
    
    if(x == null || typeof x == 'undefined') x = 1;
    if(y == null || typeof y == 'undefined') y = 1;
    
    var win = $(window);
    
    var viewport = {
        top : win.scrollTop(),
        left : win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();
    
    var height = this.outerHeight();
    var width = this.outerWidth();
 
    if(!width || !height){
        return false;
    }
    
    var bounds = this.offset();
    bounds.right = bounds.left + width;
    bounds.bottom = bounds.top + height;
    
    var visible = (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
    
    if(!visible){
        return false;   
    }
    
    var deltas = {
        top : Math.min( 1, ( bounds.bottom - viewport.top ) / height),
        bottom : Math.min(1, ( viewport.bottom - bounds.top ) / height),
        left : Math.min(1, ( bounds.right - viewport.left ) / width),
        right : Math.min(1, ( viewport.right - bounds.left ) / width)
    };
    
    return (deltas.left * deltas.right) >= x && (deltas.top * deltas.bottom) >= y;
    
};

function toggleDragging(onOff) {
    $("body").find("*").each(function() {
        if (onOff) {
            $(this).attr("draggable", "true");
            $(this).attr("ondragstart", "drag(event)");
        } else {            
            $(this).attr("ondragstart", "");
        }
    });
}

///////////////////////////////////////////////////

$(document).ready(function() {
    console.log("ready!");

    if (GetURLParameter('admin') === 'true') {
        
        //pries admino nupaisyma, kad nepritaikytu jam situ dalyku
        //$("body").find("*").draggable({cursor: "crosshair"});// - cia pagalvoti apie elementu judinima puslapyje*/

        
        //////////
        
        //dropinimui pridet visiems psl elementam iskyrus pati admina: draggable="true" ondragstart="drag(event)"
        toggleDragging(true);
        $("[draggable=\"true\"]").mouseover(
            function(e) {
                e.stopPropagation();
                $(this).css("border", "1px dotted red");

            }).mouseout(
            function() {
                $(this).css("border", "");
        });
        ////////////////////////////////
        //admin
        
        controlPanel = '<div id="abtl-placeholder">&nbsp;</div>\n\
                        <div ondrop="drop(event, this)" ondragover="allowDrop(event)" class="abtl-change abtl-hidden" id="abtl-initialChange">\n\
                        <div class="abtl-deleteChange"><span>x</span></div>\n\
                        <label>Identify by</label>\n\
                        <select class="abtl-identifyBy">\n\
                            <option value="smart">Smart identification</option>\n\
                            <option value="class">Class</option>\n\
                            <option value="id">ID</option>\n\
                            <option value="name">Name</option>\n\
                            <option value="tag">Tag</option>\n\
                            <option value="content">Content</option>\n\
                        </select>\n\
                        <div class="abtl-identifierLine"><label>Identifier</label>\n\
                        <input type="text" class="abtl-identifier abtl-hidden">\n\
                        <span class="abtl-identifierLineInfo">Not set</span></div>\n\
                        <label>Action</label>\n\
                        <select class="abtl-action">\n\
                            <option value="">---</option>\n\
                            <option value="remove">Remove</option>\n\
                            <option value="changePosition">Change position</option>\n\
                            <option value="changeContent">Change content</option>\n\
                            <option value="changeStyle">Change style</option>\n\
                            <option value="changeHTML">Change HTML</option>\n\
                        </select>\n\
                        <div class="abtl-changesContent"><textarea class="abtl-hidden"></textarea></div>\n\
                        </div>';
        
        controlPanel += '<div id="abtl-controlPanel">';
        
        controlPanel += '<div class="abtl-row abtl-menu">\n\
                        <button id="abtl-newChange">+ New change</button>\n\
                        <button tab="abtl-variationA" class="abtl-variationTabHandle abtl-active">Variation A</button>\n\
                        <button tab="abtl-variationB" class="abtl-variationTabHandle">Variation B</button>\n\
                        </div>';
        
        //variation A
        controlPanel += '<div id="abtl-variationA" class="abtl-row abtl-variationTab">';
        
        controlPanel += '</div>';
        
        //variation B
        controlPanel += '<div id="abtl-variationB" class="abtl-row abtl-variationTab abtl-hidden">';
        
        controlPanel += '</div>';

        $("body").append(controlPanel);
        
        ////////////////////////////
        //logic
        
        //tabs buttons
        $('.abtl-variationTabHandle').click(function() {
            $('.abtl-active').removeClass('abtl-active');
            $(this).addClass('abtl-active');
            
            $('.abtl-variationTab').each(function() {
                $(this).removeClass('abtl-hidden');
                $(this).addClass('abtl-hidden');
            });
            
            $('#' + $(this).attr('tab')).removeClass('abtl-hidden');
        });
        
        //new change
        $('#abtl-newChange').click(function() {
            
            tab = '#' + $('.abtl-active').attr('tab');
            
            $('#abtl-initialChange').clone(true).removeClass('abtl-hidden').appendTo(tab);
        
        });
        
        //new change
        $('.abtl-action').change(function() {
            var textarea = $(this).parent().find('.abtl-changesContent textarea');
            var identifier = $(this).parent().find('.abtl-identifier');
            
            if (!$.data(this, 'abtl-currentAction'))
                $.data(this, 'abtl-currentAction', '');
            
            if ($(this).val() !== 'remove' && $(this).val() !== '' && $(this).val() !== 'changePosition') {
                if ($.data(this, 'abtl-currentAction') === 'changePosition') {
                    makeDraggable($(".ui-draggable"), false);
                    alert('a');
                }
                textarea.removeClass('abtl-hidden');
            } else {
                textarea.addClass('abtl-hidden');
                textarea.val('');
            }
            $.data(this, 'abtl-currentAction', $(this).val());
            
            identifier.change();
        });
        
        //change identifier
        $('.abtl-identifyBy').change(function() {
            var identifier = $(this).parent().find('.abtl-identifier');
            var action = $(this).parent().find('.abtl-action');
            
            if (!$.data(this, 'current'))
                $.data(this, 'current', 'smart');
            
            if (identifier.val()) {
                if (!confirm('Are you sure?')) {
                    $(this).val($.data(this, 'current')); // added parenthesis (edit)
                    return false;
                }
            }
            if ($(this).val() === 'smart') {
                identifier.addClass('abtl-hidden');
                $(this).parent().find('.abtl-identifierLineInfo').removeClass('abtl-hidden');
            } else {
                identifier.removeClass('abtl-hidden');
                $(this).parent().find('.abtl-identifierLineInfo').addClass('abtl-hidden');
            }
            
            routeChanges($.data(this, 'current'), identifier.val(), '');
            action.val('');
            identifier.val('');
            action.change();            
            identifier.change();
            
            $.data(this, 'current', $(this).val());
        });
        
        //yra identifier? magic happens here        
        $('.abtl-identifier').change(function() {
            
            if ($(this).val().length > 0) {
                $(this).parent().parent().find('.abtl-identifierLineInfo').text('Set');
                
                var identifyBy = $(this).parent().parent().find('.abtl-identifyBy').val();
                var identifier = $(this).val();
                var action = $(this).parent().parent().find('.abtl-action').val();
                //do stuff
                routeChanges(identifyBy, identifier, action);
                
            } else {
                $(this).parent().parent().find('.abtl-identifierLineInfo').text('Not set');
            }
        });
        
        $('.abtl-identifier').blur(function () {
            $(this).change();
        });
        
        //remove change
        $('.abtl-deleteChange span').click (function () {
            $(this).parent().parent().find('.abtl-action').val('');
            $(this).parent().parent().find('.abtl-action').change();
            $(this).closest('.abtl-change').remove();            
        });  
    }
    
    function safeSelector(selector) {
        try {
            var element = $(selector);
        } catch(error) {
            return false;
        }
        return element;
    }
    
    function makeDraggable(ev, onOff) {
        if (onOff) {
            ev.each(function() {
                $(this).mouseover(
                function() {
                    //e.stopPropagation();
                    toggleDragging(false);
                    $(this).draggable({cursor: "crosshair"});
                    $(this).draggable("enable");
                    $(this).draggable("option", "containment", "document");
            }).mouseout(
                function() {                    
                    $(this).draggable("disable");
                    toggleDragging(true);
            });
        });
        } else {
            ev.each(function() {
                $(this).mouseover(function(){}).mouseout(function(){});
                $(this).draggable("disable");
            });            
        }       
    }
    
    function routeChanges(identifyByWhat, identifier, action) {
        if (identifier.length > 0) {
            //smart selection (anything goes)
            if (identifyByWhat === 'smart') {
                elements = safeSelector(identifier);
                if (elements.length > 0) {
                    elements.each(function () {
                        applyChanges(action, $(this));
                    });
                } else {
                    $("*").each(function () {
                        if (customTrim($(this).text()) === identifier) {
                            applyChanges(action, $(this));
                        }
                    });
                }
            }
            
            if (identifyByWhat === 'content') { //identify by content
                $("*").each(function () {
                    if (customTrim($(this).text()) === identifier) {
                        applyChanges(action, $(this));
                    }
                });
            } else if (identifyByWhat === 'class') { //identify by class
                safeSelector("." + identifier).each(function () {
                    applyChanges(action, $(this));
                });
            } else if (identifyByWhat === 'id') { //identify by ID
                safeSelector("#" + identifier).each(function () {
                    applyChanges(action, $(this));
                });
            } else if (identifyByWhat === 'name') { //identify by name
                safeSelector("[name='" + identifier + "']").each(function () {
                    applyChanges(action, $(this));
                });
            } else if (identifyByWhat === 'tag') { //identify by tag
                safeSelector(identifier).each(function () {
                    applyChanges(action, $(this));
                });
            }
        }
    }
    
    function applyChanges(action, handle) {
        if (action === 'remove') {
            handle.css("display", "none");
        } else if (action === 'changePosition') {
            makeDraggable(handle, true);                        
        } else {
            handle.css("display", "");
        }        
    }
    
    
	//$("body").load("http://www.mebeles1.lv/abtl/test.php", function() {
	//alert( "Load was performed!.");
//});
});