(function(ns){
    ns.log = function(message, styleClass){
        if(styleClass==undefined)
            styleClass='defaultText';
        var d = new Date();
        var dateStr = d.toLocaleTimeString();
        $('#systemLog').prepend('<div class="'+styleClass+'">' + dateStr + ' - ' + message + '</div>');
    };
})(window.main = window.main || {});