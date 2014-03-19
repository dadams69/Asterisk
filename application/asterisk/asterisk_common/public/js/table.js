$(document).ready(function() {
    
    function search_holder_scripts(){
        if($('.datepicker').length>0)
            $('.datepicker').daterangepicker(); 
        
        $(".controls").each(function(){
            if($(this).find(".datepicker-group").length>0)
                $(this).find(".datepicker-group").daterangepicker(); 
        });
    }
    
    var History = window.History; 
    
    // Bind to State Change
        History.Adapter.bind(window,'statechange',function(){ // Note: We are using statechange instead of popstate
            // Log the State
            $('#message-holder').html("");
            var State = History.getState(); // Note: We are using History.getState() instead of event.state
            console.log(State);
            $.get(
            State.url,
            function(data) {
                if (data.message) {
                    show_message(data.message.type,data.message.text);
                }
                if (data.content) {
                    if (data.content.table) {    
                        $('#table-holder').html(data.content.table);
                    }
                    if (data.content.pagination) {    
                        $('#pagination-holder').html(data.content.pagination);
                    }
                    if (data.content.search) {  
                        $('#search-holder').html(data.content.search);
                        search_holder_scripts();
                    }
                }
            },
            "json");
        });
    
    
        $(document).on("click", "a.ajax", function(e) {
            if (History.enabled) {
                $url = $(this).attr("href");
                History.pushState(null, null, $url);  //replaceState  
                return false;
            }
        });
    
        $(document).on("submit", "form.ajax", function() {
            if (History.enabled) {
                History.pushState(null, null, ($(this).attr("action") + "?" + $(this).serialize()));
                return false;
            }
        });
        
        function show_message(type, text){
            $('#message-holder').html("");
            var $message_div = $("<div>").addClass("alert alert-" + type + " fade in").html("<a class='close' data-dismiss='alert' href='#'>×</a> " + text);
            $message_div.hide();
            $('#message-holder').append($message_div);
            $message_div.fadeIn();            
        }
        
        $(document).on("click", "a.multiaction", function(e) {
                if($("#table-form input:checked").length > ""){
                    var $link = $(this);
                    if($link.hasClass("use_dialog")){
                        $(this).dialog_confirmation({on_confirm: function() {
                                    var $url = $link.attr("href") + "?" + $("#table-form").serialize();
                                    if (!History.enabled) {                       
                                        document.location.href = $url;
                                    } else {
                                    $.get($url,function(data) {
                                            if (data.message) {
                                                show_message(data.message.type,data.message.text);
                                            }
                                            if (data.content) {
                                                if (data.content.table) {    
                                                    $('#table-holder').html(data.content.table);
                                                }
                                                if (data.content.pagination) {    
                                                    $('#pagination-holder').html(data.content.pagination);
                                                }
                                                if (data.content.search) {    
                                                    $('#search-holder').html(data.content.search);
                                                    search_holder_scripts();
                                                }
                                            }
                                        },
                                    "json");
                                    }
                        }});
                    }else{
                        var $url = $link.attr("href") + "?" + $("#table-form").serialize();
                        if (!History.enabled) {                       
                            document.location.href = $url;
                        } else {
                        $.get($url,function(data) {
                                if (data.message) {
                                    show_message(data.message.type,data.message.text);
                                }
                                if (data.content) {
                                    if (data.content.table) {    
                                        $('#table-holder').html(data.content.table);
                                    }
                                    if (data.content.pagination) {    
                                        $('#pagination-holder').html(data.content.pagination);
                                    }
                                    if (data.content.search) {    
                                        $('#search-holder').html(data.content.search);
                                        search_holder_scripts();
                                    }
                                }
                            },
                        "json");
                        }                        
                    }
                }else{
                    show_message("info","You must select at least one item.");
                }
                e.preventDefault();
        });
        
        $(document).on("click", "a.select-all-action", function(e) {
                $("#table-form input:checkbox").attr("checked","checked");
                e.preventDefault();
        });
        
        $(document).on("click", "a.unselect-all-action", function(e) {
                $("#table-form input:checkbox").removeAttr("checked");
                e.preventDefault();
        }); 
        
        $(document).on("click", "a.toggle-all-action", function(e) {
                var $checkboxes = $("#table-form input:checkbox");
                $checkboxes.each(function(){
                    if($(this).is(':checked'))
                        $(this).removeAttr("checked");
                    else
                        $(this).attr("checked","checked");  
                });
                e.preventDefault();
        });              
        
               
        search_holder_scripts();
    
});
