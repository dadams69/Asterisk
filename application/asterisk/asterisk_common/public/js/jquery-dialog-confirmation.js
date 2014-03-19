jQuery(function(){
    $.fn.dialog_confirmation = function(options){
        var title = "Confirm?";
        var message = "Are you sure?";
            
        if (typeof options.title != 'undefined') title = options.title;
        if (typeof options.message != 'undefined') message = options.message;
        
        var $dialog = $("<div id='dialog-holder'></div>");
        $dialog.html(message);
        $dialog.dialog( {
            'modal': true,
            'bgiframe': true,
            'width': 500,
            'height': 200,
            'autoOpen': false, 
            'title' : title,
            'buttons' : {
                "Confirm" : function(){
                    $(this).dialog("close");
                    if (typeof options.on_confirm != 'undefined') options.on_confirm()    
                },
                "Cancel" : function() {
                    $(this).dialog("close");
                }
            }
        });
        $dialog.dialog("open");                      
    };
    
    $(".dialog_confirm_direct").click(function(event){
        var $elm = $(this);
        $elm.dialog_confirmation({on_confirm: function(){
            var _href = "";    
            var _target = "_self";
            if (typeof $elm.attr("href") != 'undefined') _href = $elm.attr("href");
            if (typeof $elm.attr("target") != 'undefined') _target = $elm.attr("target");
            window.open(_href,_target)
        },
        title: $elm.attr("confirmation_title"),
        message: $elm.attr("confirmation_message")
        });
        event.preventDefault();
    });

});