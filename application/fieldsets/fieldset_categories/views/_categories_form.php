<section>
    <div class="page-header">
        <h1>Edit Categories
            <a href="#" id="end_edit_categories_link">
                <i class="icon-remove"></i>
            </a>            
        </h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_categories/categories_form/" . $fieldset_categories_id); ?>" method="post" id="category_three_form">                     
        <input type="hidden" name="form[fieldset_categories_id]" value="<?php echo $fieldset_categories_id; ?>" />
        <fieldset id="category_three">
            <legend><?php echo $group["name"]; ?></legend>
            <?php echo display_category($form, -1); ?>
            <script type="text/x-jquery-tmpl" class="element_template">
                <div class="category_group">
                    <div class="category">
                        <div class="input-prepend">
                            <input type="hidden" class="input_fieldset_categories_data_id" name="form[category][${fieldset_categories_data_id}][fieldset_categories_data_id]" value="${fieldset_categories_data_id}" />
                            <input type="hidden" class="input_category_name" name="form[category][${fieldset_categories_data_id}][category_name]" value="${category_name}" />
                            <input type="hidden" class="input_key" name="form[category][${fieldset_categories_data_id}][key]" value="${key}" />                            
                            <input type="hidden" class="input_rank" name="form[category][${fieldset_categories_data_id}][rank]" value="${rank}" />                            
                            <input type="hidden" class="input_parent_fieldset_categories_data_id" name="form[category][${fieldset_categories_data_id}][parent_fieldset_categories_data_id]" value="${parent_fieldset_categories_data_id}" />                            
                            <input type="hidden" class="input_status" name="form[category][${fieldset_categories_data_id}][status]" value="new" />                            
                            <a href="#" class="btn link_category_name" style="width: 240px;border-radius: 4px 0 0 4px;display: inline-block;overflow: hidden;text-align: left;vertical-align: middle;">${category_name}</a>
                            <a href="#" class="btn btn-primary edit_category"><i class="icon-edit icon-white"></i></a>
                            <a href="#" class="btn btn-success add_category"><i class="icon-plus-sign icon-white"></i></a>
                            <a href="#" class="btn btn-warning delete_category" style="border-radius: 0 4px 4px 0;"><i class="icon-remove icon-white"></i></a>
                        </div>
                    </div>
                </div>
            </script>                    
        </fieldset>
        <fieldset>
            <br/>
            <a href="#" class="btn btn-success add_new_category">Add Category</a>
        </fieldset>        
        <fieldset>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?php echo site_url("/fieldset_categories/categories_form/" . $fieldset_categories_id); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
    <div id="category_modal">
        <div id="category_form_modal" class="modal hide fade" style="width:700px;margin-left:-350px;">
            <div id="modal_work_area">    
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Category:</h3>
                </div>
                <div class="modal-body" style="padding-top: 0px">
                    <div class="category_data_modal_form">
                        <form class="form-horizontal" action="#" method="post" id="category_data_form">
                            <div id="modal-message-holder" style="margin-top:4px;"></div>
                            <input type="hidden" name="form[fieldset_categories_data_id]" id="fieldset_categories_data_id" value=""/>
                            <fieldset> 
                            <?php
                            form_field_input(array(
                                "label" => "Category Name",
                                "name" => "form[category_name]",
                                "id" => "category_name",
                                "type" => "text",
                                "input_class" => "input-xlarge",
                                "value" => "",
                                "control_group_class" => get_if_set($validation, "fields>category_name>message>type"),
                                "help_inline" => get_if_set($validation, "fields>category_name>message>text")
                            ));
                            ?>                                
                            <?php
                            form_field_input(array(
                                "label" => "Key",
                                "name" => "form[key]",
                                "id" => "key",
                                "type" => "text",
                                "input_class" => "input-xlarge",
                                "value" => "",
                                "control_group_class" => get_if_set($validation, "fields>key>message>type"),
                                "help_inline" => get_if_set($validation, "fields>key>message>text")
                            ));
                            ?>
                            </fieldset>
                            </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary modal_action_form_save">Save</button>
                    <a href="#" class="btn modal_action_form_cancel">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    jQuery(function(){   
        loadCSS("/application/fieldsets/fieldset_categories/public/categories.css");
        head.js(
            {jquery_tmpl: "/application/asterisk/asterisk_common/public/js/jquery.tmpl.js"}
        );
        head.ready(function() {
            
            $("a#end_edit_categories_link").click(function(e){
                $.post("<?php echo site_url("/fieldset_categories/form/".$fieldset_categories_id); ?>",function(data){
                    $("#work_area").html(data.content.work_area);
                },"json");
                e.preventDefault();
            });            
            
            
            var $fieldset = $("fieldset#category_three");
            var $template = $fieldset.find(".element_template");
            var next_element_id = 0;
            
            function set_modal($link){
                var category_input_placeholder = $link.parent();
                
                var $modal_placeholder = $("#category_modal")
                var $modal = $modal_placeholder.find("#category_form_modal").modal();
                
                var fieldset_categories_data_id = category_input_placeholder.find("input.input_fieldset_categories_data_id").val();    
                var category_name = category_input_placeholder.find("input.input_category_name").val();    
                var key = category_input_placeholder.find("input.input_key").val();    
                $modal.find("input#fieldset_categories_data_id").val(fieldset_categories_data_id);                
                $modal.find("input#category_name").val(category_name);
                $modal.find("input#key").val(key);  
                
                // ADD CANCEL HANDLER
                $modal_placeholder.off("click","a.modal_action_form_cancel");
                $modal_placeholder.on("click","a.modal_action_form_cancel", function (event){
                    
                    $modal.modal("hide");
                    $("#main_container #message-holder div").fadeOut();                            
                    $("#modal-message-holder div").fadeOut();                
                    event.preventDefault();
                });

                // ADD SAVE HANDLER
                $modal_placeholder.off("click","button.modal_action_form_save");
                $modal_placeholder.on("click","button.modal_action_form_save", function (event){
                    category_input_placeholder.find("a.link_category_name").html($modal.find("input#category_name").val());
                    category_input_placeholder.find("input.input_category_name").val($modal.find("input#category_name").val());
                    category_input_placeholder.find("input.input_key").val($modal.find("input#key").val());
                    $modal.modal("hide");
                    event.preventDefault();
                });                                        
            }
            
            $("a.add_new_category").click(function(event){
                var $container = $("#category_three");
                var rank = $container.find("div.category_group").length;
                rank++;
                var parent_id = "";
                var new_element = $template.tmpl({
                    fieldset_categories_data_id: "new_"+next_element_id,    
                    category_name: "New category",
                    key: "new_category",
                    rank: rank,
                    parent_fieldset_categories_data_id: parent_id,
                    validation: {}
                    //specify other default values
                })
                
                new_element.appendTo($container);                
                next_element_id++;
                
                set_modal(new_element.find("a.edit_category"));
                set_ranks($("#category_three"));                
                new_element.draggable(item_draggable);
                event.preventDefault();
            });
            
            $fieldset.on("click","a.add_category",function(event){
                var $container = $(this).closest("div.category_group");
                var rank = $container.find("div.category_group").length;
                rank++;
                var parent_id = $(this).parent().find("input.input_fieldset_categories_data_id").val();
                var new_element = $template.tmpl({
                    fieldset_categories_data_id: "new_"+next_element_id,    
                    category_name: "New category",
                    key: "new_category",
                    rank: rank,
                    parent_fieldset_categories_data_id: parent_id,
                    validation: {}
                    //specify other default values
                })
                
                new_element.appendTo($container);                
                next_element_id++;
                
                set_modal(new_element.find("a.edit_category"));
                set_ranks($("#category_three"));
                new_element.draggable(item_draggable);
                event.preventDefault();
            });
            
            $fieldset.on("click","a.delete_category",function(event){
                var $container = $(this).closest("div.category_group");
                $(this).dialog_confirmation({on_confirm: function(){ 
                    $container.remove();
                    set_ranks($("#category_three"));
                }});
                event.preventDefault();
            });
            
            $fieldset.on("click","a.edit_category",function(event){
                set_ranks($("#category_three"));
                set_modal($(this));
                event.preventDefault();
            });                
            
            function set_ranks($base_three){
                var rank = 1;
                $base_three.children(".category_group").each(function(){
                    $(this).children(".category").find("input.input_rank").val(rank);
                    if($base_three.children(".category").find(".input-prepend input.input_fieldset_categories_data_id").val()!== undefined){
                        $(this).children(".category").find("input.input_parent_fieldset_categories_data_id").val($base_three.children(".category").find(".input-prepend input.input_fieldset_categories_data_id").val());
                    }else{
                        $(this).children(".category").find("input.input_parent_fieldset_categories_data_id").val("");
                    }
                    rank++;
                    set_ranks($(this));
                });
            }
            
            
            var item_draggable = {
                containment: '#category_three_form',
                opacity: 0.5,
                helper: 'clone',
                revert: 'invalid',
                revertDuration: 100,                 
                stop: function(){ 
                    $(".component_above, .component_below, .component_child").remove();
                    set_ranks($("#category_three"));
                },
                start: function(event, ui){
                    $("#category_three .category_group").not(ui.helper).not(this).each(function(){
                            $(this).children(".category").find(".input-prepend").append('<span class="component_above" >&nbsp;</span>');
                            $(this).children(".category").find(".input-prepend").append('<span class="component_below">&nbsp;</span>');
                            $(this).children(".category").find(".input-prepend").append('<span class="component_child">&nbsp;</span>');
                    });       
                    $(".component_above").droppable({
                            accept      : ".category_group",
                            tolerance   : "pointer",
                            hoverClass  : "component_hover",
                            drop        : function(event, ui){
                                component_droppable(ui.draggable,$(this).closest(".category_group"), "prev");
                            }
                    }) ;   
                    $(".component_below").droppable({
                            accept      : ".category_group",
                            tolerance   : "pointer",
                            hoverClass  : "component_hover",
                            drop        : function(event, ui){
                                component_droppable(ui.draggable,$(this).closest(".category_group"), "next");
                            }
                    }) ;   
                    $(".component_child").droppable({
                            accept      : ".category_group",
                            tolerance   : "pointer",
                            hoverClass  : "component_hover",
                            drop        : function(event, ui){
                                component_droppable(ui.draggable,$(this).closest(".category_group"), "child");
                            }
                    }) ;   
                }
            }
          
            
            
            $('.category_group').draggable(item_draggable);
            
            function component_droppable(dragged_element,drop_area,located){
                    
                    if(located=='next'){
                        dragged_element.insertAfter(drop_area);
                    }else if(located=='prev'){
                        dragged_element.insertBefore(drop_area);
                    }else if(located=='child'){
                        dragged_element.appendTo(drop_area);
                    }
                    set_ranks($("#category_three"));
            }            
        });

    });        
   
</script>

<?php

function display_category($form, $id) {
    if (isset($form[$id])) {
        foreach($form[$id] as $row) {
        ?>
                <div class="category_group">
                    <div class="category">
                        <div class="input-prepend">
                            <input type="hidden" class="input_fieldset_categories_data_id" name="form[category][<?php echo get_if_set($row,"fieldset_categories_data_id"); ?>][fieldset_categories_data_id]" value="<?php echo get_if_set($row,"fieldset_categories_data_id"); ?>" />
                            <input type="hidden" class="input_category_name" name="form[category][<?php echo get_if_set($row,"fieldset_categories_data_id"); ?>][category_name]" value="<?php echo get_if_set($row,"category_name"); ?>" />
                            <input type="hidden" class="input_key" name="form[category][<?php echo get_if_set($row,"fieldset_categories_data_id"); ?>][key]" value="<?php echo get_if_set($row,"key"); ?>" />                            
                            <input type="hidden" class="input_rank" name="form[category][<?php echo get_if_set($row,"fieldset_categories_data_id"); ?>][rank]" value="<?php echo get_if_set($row,"rank"); ?>" />                            
                            <input type="hidden" class="input_parent_fieldset_categories_data_id" name="form[category][<?php echo get_if_set($row,"fieldset_categories_data_id"); ?>][parent_fieldset_categories_data_id]" value="<?php echo get_if_set($row,"parent_fieldset_categories_data_id"); ?>" />                            
                            <input type="hidden" class="input_status" name="form[category][<?php echo get_if_set($row,"fieldset_categories_data_id"); ?>][status]" value="not_new" />                            
                            <a href="#" class="btn link_category_name" style="width: 240px;border-radius: 4px 0 0 4px;display: inline-block;overflow: hidden;text-align: left;vertical-align: middle;"><?php echo $row["category_name"]; ?></a>
                            <a href="#" class="btn btn-primary edit_category"><i class="icon-edit icon-white"></i></a>
                            <a href="#" class="btn btn-success add_category"><i class="icon-plus-sign icon-white"></i></a>
                            <a href="#" class="btn btn-warning delete_category" style="border-radius: 0 4px 4px 0;"><i class="icon-remove icon-white"></i></a>
                        </div>
                    </div>
                    <?php display_category($form, $row["fieldset_categories_data_id"]); ?>       
                </div>
        <?php
        }
    }
}
