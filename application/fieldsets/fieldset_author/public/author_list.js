jQuery(function(){
        
    $.fn.extend({
        author_list: function(options){                                
            $(this).each(function(){
                var $link = $(this);
                $parent = $link.parent();
                var $modal_placeholder = $("#author_modal");
                var modal;

                $parent.off("click","a#author_modal_link");
                $parent.on("click","a#author_modal_link",function(event){
                    
                    $.post("/fieldset_author/fieldset_author_data_list/",function(data){
                        $modal_placeholder.html(data.content.modal);
                        $modal = $("#author_list_modal").modal();
                        
                        $modal_placeholder.off("click","a.modal_action_edit");
                        $modal_placeholder.on("click","a.modal_action_edit",function(event){
                            var $modal_action_edit = $(this);
                            $.post($modal_action_edit.attr("href"),function(data){
                                
                                // UPDATE PAGE
                                
                                $modal_placeholder.find("#modal_work_area").html("");
                                $modal_placeholder.find("#modal_work_area").html(data.content.modal);
                                if (typeof data.message != 'undefined') {
                                    $("#modal-message-holder").html('<div z-index="99999" class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                                } else {
                                    $("#modal-message-holder div").fadeOut();                
                                }
                                
                                // ADD CANCEL HANDLER
                                $modal_placeholder.off("click","a.modal_action_form_cancel");
                                $modal_placeholder.on("click","a.modal_action_form_cancel", function (event){
                                    $.post("/fieldset_author/fieldset_author_data_list/partial",function(data){
                                        $modal_placeholder.find("#modal_work_area").html("");
                                        $modal_placeholder.find("#modal_work_area").html(data.content.modal);
                                        if (typeof data.message != 'undefined') {
                                            $("#modal-message-holder").html('<div z-index="99999" class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                                        } else {
                                            $("#modal-message-holder div").fadeOut();                
                                        }
                                        event.preventDefault();                            
                                    },"json");
                                    event.preventDefault();
                                });
                                
                                // ADD SAVE HANDLER
                                $modal_placeholder.off("click","button.modal_action_form_save");
                                $modal_placeholder.on("click","button.modal_action_form_save", function (event){
                                    $.post($("form#author_list_form").attr("action"),$("form#author_list_form").serialize(),function(data){
                                        $modal.find("#modal_work_area").html("");
                                        $modal.find("#modal_work_area").html(data.content.modal);
                                        if (typeof data.message != 'undefined') {
                                            $("#modal-message-holder").html('<div z-index="99999" class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                                        } else {
                                            $("#modal-message-holder div").fadeOut();                
                                        }
                                        event.preventDefault();                           
                                    },"json");
                                    event.preventDefault();
                                });
                                event.preventDefault();                            
                            },"json");
                            event.preventDefault();
                        });
                        
                        $modal_placeholder.off("click","a.modal_action_remove");
                        $modal_placeholder.on("click","a.modal_action_remove",function(event){
                            $.post($(this).attr("href"),function(data){
                                $modal.find("#modal_work_area").html(data.content.modal);
                                if (typeof data.message != 'undefined') {
                                    $("#modal-message-holder").html('<div z-index="99999" class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                                } else {
                                    $("#modal-message-holder div").fadeOut();                
                                }
                            },"json");
                            event.preventDefault();
                        });                        
                        
                        $modal_placeholder.off("click","a.modal_action_create");
                        $modal_placeholder.on("click","a.modal_action_create",function(event){
                            $.post("/fieldset_author/fieldset_author_data_form/",function(data){
                                $modal_placeholder.find("#modal_work_area").html("");
                                $modal_placeholder.find("#modal_work_area").html(data.content.modal);
                                $modal_placeholder.off("click","a.modal_action_form_cancel");
                                $modal_placeholder.on("click","a.modal_action_form_cancel", function (event){
                                    $.post("/fieldset_author/fieldset_author_data_list/partial",function(data){
                                        $modal_placeholder.find("#modal_work_area").html("");
                                        $modal_placeholder.find("#modal_work_area").html(data.content.modal);
                                        if (typeof data.message != 'undefined') {
                                            $("#modal-message-holder").html('<div z-index="99999" class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                                        } else {
                                            $("#modal-message-holder div").fadeOut();                
                                        }                                       
                                        event.preventDefault();                            
                                    },"json");
                                    event.preventDefault();
                                });
                                $modal_placeholder.off("click","button.modal_action_form_save");
                                $modal_placeholder.on("click","button.modal_action_form_save", function (event){
                                    $.post($("form#author_list_form").attr("action"),$("form#author_list_form").serialize(),function(data){
                                        $modal_placeholder.find("#modal_work_area").html("");
                                        $modal_placeholder.find("#modal_work_area").html(data.content.modal);
                                        if (typeof data.message != 'undefined') {
                                            $("#modal-message-holder").html('<div z-index="99999" class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                                        } else {
                                            $("#modal-message-holder div").fadeOut();                
                                        }
                                        event.preventDefault();                           
                                    },"json");
                                    event.preventDefault();
                                });
                                event.preventDefault();                            
                            },"json");
                        });
                        
                    },"json");
                    event.preventDefault();
                });

            });
        }
    });
});