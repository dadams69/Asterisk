jQuery(function(){
        
    $.fn.extend({
        file_input: function(options){
            if (typeof options == 'undefined') {
              options = {};
            }
            
            function set_image_on_container($input){
                $.post("/fieldset_file/partial_file/"+$input.val(),function(data){
                    $input.parent().find(".file_partial").remove();
                    var $content = $(data.content.file);
                    $content.insertAfter($input);
                },"json");                
            }
            
            
            $(this).each(function(){
                var $input = $(this);
                var $parent = $input.parent();
                var $modal;
                $parent.off("click","a.modal_action");
                $parent.on("click","a.modal_action",function(event){
                    var par_base_query = "";
                    var par_custom_query = "";
                    var par_limit_to_version = "true";

                    if($input.attr("base_query") !== undefined) par_base_query = $input.attr("base_query");
                    if($input.attr("custom_query") !== undefined) par_base_query = $input.attr("custom_query");
                    if($input.attr("limit_to_version") !== undefined) par_base_query = $input.attr("limit_to_version");                    
                    
                    $.post("/fieldset_file/partial_file_select_modal/modal/" + $input.attr("version_id"),{base_query: par_base_query, custom_query:par_custom_query, limit_to_version: par_limit_to_version},function(data){
                        $parent.find("#myModal").remove();
                        $parent.append(data.content.modal);
                        $modal = $parent.find("#myModal");
                        $modal.modal();

                        //WE COULD AVOID DO THIS BY SETTING IT UP ON THE PARTIAL BASED IN PARAMETERS
                        if(par_limit_to_version == "true"){
                            $modal.find("input.search-include-version").attr('checked','checked');
                        }
                        
                        function research_files(){
                            if($modal.find("input.search-include-version").is(":checked")){
                                par_limit_to_version = "true";
                            }else{
                                par_limit_to_version = "false";
                            }
                            $.post("/fieldset_file/partial_file_select_modal/results/" + $input.attr("version_id"),{base_query: par_base_query, custom_query: $modal.find("input.search-query").val(), limit_to_version: par_limit_to_version},function(data){
                                $modal.find("div.file_select_modal_results").replaceWith(data.content.results);
                                $modal.find("a.select_action").click(function(event){
                                    $input.val($(this).attr("data-file-data-id"));
                                    set_image_on_container($input);
                                    $modal.modal("hide");
                                    if(options.after_input_value_change !== undefined) options.after_input_value_change($input);
                                    event.preventDefault();
                                });                                    
                            },"json");                            
                        }
                        
                        $modal.find("input.search-include-version").change(function(e) {
                            research_files();
                        });

                        $modal.find("input.search-query").keypress(function(e) {
                            if(e.which == 13) {
                                research_files();
                                e.preventDefault();
                            }
                        });

                        $modal.find("a.select_action").click(function(event){
                            $input.val($(this).attr("data-file-data-id"));
                            set_image_on_container($input);
                            $modal.modal("hide");
                            if(options.after_input_value_change !== undefined) options.after_input_value_change($input);
                            event.preventDefault();
                        });
                    },"json");
                    event.preventDefault();
                });
                
                $parent.off("click","a.remove_file_action");
                $parent.on("click","a.remove_file_action",function(event){
                    $input.val("");
                    set_image_on_container($input);
                    if(options.after_input_value_change !== undefined) options.after_input_value_change($input);
                    if(options.after_remove !== undefined) options.after_remove($input);
                    event.preventDefault();
                });
                
                set_image_on_container($input);

            });
        }
    });
});