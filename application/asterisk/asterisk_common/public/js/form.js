$(document).ready(function() {

    function handle_form($form){      
        $("#message-holder").html(""); 
        $.post($form.attr("action"),$form.serialize(), function(data){
            handle_response(data);
        },"json");        
    }

    function handle_link($link){      
        $("#message-holder").html("").hide(); 
        $.post($link.attr("href"), function(data){
            handle_response(data);
        },"json");   
    }

    function handle_response(data){
        if (typeof data.message != 'undefined') {
            $("#message-holder").html('<div class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
        }
        if (typeof data.content != 'undefined') {
            $.each(data.content, function(id, html) {
                if($("#"+id).hasClass("fade_on_change")){
                    $("#"+id).hide().html(html).fadeIn();
                }else{
                    $("#"+id).html(html);
                }
            });                
        }        
    }

    $("header#overview").on("click",".version_state_dropdown ul.dropdown-menu li>a", function(event){
        if($(this).hasClass("dialog_confirm")){
            var $link =$(this);
            $(this).dialog_confirmation({on_confirm: function(){
                handle_link($link);
            }});
            event.preventDefault();
        }else{
            handle_link($(this));
            event.preventDefault();
        }
    });
    
    $("header#overview").on("click","#content_type_dropdown ul.dropdown-menu li>a", function(event){
        if($(this).hasClass("dialog_confirm")){
            var $link =$(this);
            $(this).dialog_confirmation({on_confirm: function(){
                handle_link($link);
            }});
            event.preventDefault();
        }else{
            handle_link($(this));
            event.preventDefault();
        }
    });
    
    $("#main_container").on("click","#navigation ul.fieldset_dropdown li>a,#navigation ul.fieldset_nav li>a, #work_area .form-actions a,#work_area a.close-actions, a.action_link", function(event){
        if($(this).hasClass("dialog_confirm")){
            var $link =$(this);
            $(this).dialog_confirmation({on_confirm: function(){
                handle_link($link);
            }});
            event.preventDefault();
        }else{
            handle_link($(this));
            event.preventDefault();
        }
    });    
    $("#main_container").on("click","#navigation ul.fieldset_nav li>a", function(event){
        $(this).parents("ul.fieldset_nav").find("li").removeClass("active");
        $(this).parent().addClass("active")
        event.preventDefault();
    });    
    
    $("#main_container").on("submit","form", function(event){
        handle_form($(this));
        event.preventDefault();
    });        


    $("#header_container").on("click","#content_name_edit",function(event){
        $("#content_name_span_view").hide();
        $("#content_name_span_edit").show();
        event.preventDefault();
    });
    $("#header_container").on("click","#content_name_cancel",function(event){
        $("#content_name_span_edit input#content_name_input").val($("#content_name_span_edit input#content_name_input").attr("pre_value"));
        $("#content_name_span_edit").hide();
        $("#content_name_span_view").show();
        event.preventDefault();
    });    
    $("#header_container").on("click","#content_name_save",function(event){
        $("#header_container form").submit();
        event.preventDefault();
    });    
    $("#header_container").on("submit","#content_name_form",function(event){
        handle_form($(this));
        event.preventDefault();
    });
    
    var getScript = jQuery.getScript;
    jQuery.getScript = function( resources, callback ) {

        var // reference declaration &amp; localization
        length = resources.length,
        handler = function() { counter++; },
        deferreds = [],
        counter = 0,
        idx = 0;

        for ( ; idx < length; idx++ ) {
            deferreds.push(
                getScript( resources[ idx ], handler )
            );
        }

        jQuery.when.apply( null, deferreds ).then(function() {
            callback && callback();
        });
    };    
    
});
