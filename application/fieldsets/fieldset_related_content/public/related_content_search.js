jQuery(function(){
        
    $.fn.extend({
        related_content_search: function(options){

            $(this).each(function(){
                
                var $template = $(document).find(".related_content_template");
        
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
                            existing_content_id : function(){
                                var existing_content_id = "";
                                $input_autocomplete.parents("fieldset").find(".added_content").each(function(){
                                    if(existing_content_id == ""){
                                        existing_content_id = $(this).val();
                                    }else{
                                        existing_content_id = existing_content_id + "," + $(this).val();
                                    }
                                })
                                return existing_content_id;
                            }                            
                          },
                          success: function(data) {
                            response(data);
                          }
                        });
                    },
                    minLength: 1,
                    select: function(event, ui) {
                       
                       if(options.container !== undefined){
                           $(options.container).append($template.tmpl({
                                    content_id: ui.item.id,
                                    name: ui.item.name
                                }));
                       }else{
                            $template.tmpl({
                                    content_id: ui.item.id,
                                    name: ui.item.name
                            }).insertBefore($input_autocomplete) ;                           
                       }
                        if(options.after_select !== undefined) options.after_select();
                        $input_autocomplete.val("");
                        event.preventDefault();
                   }
                }).data("autocomplete")._renderItem = function (ul, item) {  
                    var $row;

                    $row = $("<li></li>").data("item.autocomplete", item).append(
                         "<a href='#'>" +
                            item.name
                        + "</a>");
                    
                        
                    $row.appendTo(ul);
                    
                    return $row;
                };

            });
        }
    });
});



