jQuery(function(){
        
    $.fn.extend({
        tags_search: function(options){

            $(this).each(function(){
                
                var $existing_tag_template = $(document).find(".existing_tag_template");
                var $new_tag_template = $(document).find(".new_tag_template");
        
                var $input_autocomplete = $(this);  

                $input_autocomplete.keypress(function(event){
                    var keycode = (event.keyCode ? event.keyCode : event.which);
                    if (keycode == '13') {
                      event.preventDefault();
                      event.stopPropagation();    
                    }
                });
                
                var search_url = $(this).attr("search_url");
               
                $input_autocomplete.autocomplete({
                    source: function(request, response) {
                        $.ajax({
                          url: search_url,
                          dataType: "json",
                          data: {
                            term : request.term,
                            existing_tags_id : function(){
                                var existing_tags_id = "";
                                $input_autocomplete.parents("fieldset").find(".existing_tag").each(function(){
                                    if(existing_tags_id == ""){
                                        existing_tags_id = $(this).val();
                                    }else{
                                        existing_tags_id = existing_tags_id + "," + $(this).val();
                                    }
                                })
                                return existing_tags_id;
                            },
                            new_tags : function(){
                                var new_tags = "";
                                $input_autocomplete.parents("fieldset").find(".new_tag").each(function(){
                                    if(new_tags == ""){
                                        new_tags = $(this).val();
                                    }else{
                                        new_tags = new_tags + "||" + $(this).val();
                                    }
                                })
                                return new_tags;
                            }                            
                          },
                          success: function(data) {
                            response(data);
                          }
                        });
                    },
                    minLength: 1,
                    select: function(event, ui) {
                        var $template;
                        if(ui.item.id != -1){
                            $template = $existing_tag_template;
                        }else{
                            $template = $new_tag_template;
                        }
                        $template.tmpl({
                                tag_id: ui.item.id,
                                group_key: ui.item.group_key,
                                name: ui.item.name
                        }).insertBefore($input_autocomplete) ;
                        if(options.after_select !== undefined) options.after_select();
                        $input_autocomplete.val("");
                        event.preventDefault();
                   }
                }).data("autocomplete")._renderItem = function (ul, item) {  
                    var $row;
                    if(item.id == -1){   
                        $row = $("<li class='new_tag'></li>").data("item.autocomplete", item).append(
                            "<a href='#'>" +
                                item.name
                            + "</a>");
                            
                    }else{
                        $row = $("<li></li>").data("item.autocomplete", item).append(
                             "<a href='#'>" +
                                item.name
                            + "</a>");
                    }
                        
                    $row.appendTo(ul);
                    
                    return $row;
                };

            });
        }
    });
});



