<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/event_recap/" . get_if_set($form, "fieldset_event_recap_id")); ?>">&times;</a>
        <h1>Event Recap</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_event_recap/form/" . get_if_set($form, "fieldset_event_recap_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_event_recap_id]" value="<?php echo get_if_set($form, "fieldset_event_recap_id"); ?>" />

        <fieldset>
            <?php
            form_field_input(array(
                "label" => "Show Recap",
                "name" => "form[show_recap]",
                "id" => "show_recap",
                "type" => "checkbox",
                "input_class" => "input-xlarge",
                "value" => "show_recap",
                "checked" => get_if_set($form, "show_recap",null),
                "control_group_class" => get_if_set($validation, "fields>show_recap>message>type"),
                "help_inline" => get_if_set($validation, "fields>show_recap>message>text")
            ));
            ?>            
            <?php
            form_field_textarea(array(
                "label" => "Description",
                "name" => "form[description]",
                "id" => "description",
                "value" => get_if_set($form, "description"),
                "control_group_class" => get_if_set($validation, "fields>description>message>type"),
                "help_inline" => get_if_set($validation, "fields>description>message>text")
            ));
            ?>               
            <?php
            form_field_input(array(
                "label" => "Vimeo ID",
                "name" => "form[vimeo_id]",
                "id" => "vimeo_id",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "vimeo_id"),
                "control_group_class" => get_if_set($validation, "fields>vimeo_id>message>type"),
                "help_inline" => get_if_set($validation, "fields>vimeo_id>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Audio",
                "name" => "form[audio]",
                "id" => "audio",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "audio"),
                "control_group_class" => get_if_set($validation, "fields>audio>message>type"),
                "help_inline" => get_if_set($validation, "fields>audio>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Link",
                "name" => "form[link]",
                "id" => "link",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "link"),
                "control_group_class" => get_if_set($validation, "fields>link>message>type"),
                "help_inline" => get_if_set($validation, "fields>link>message>text")
            ));
            ?>
        </fieldset>
            <input type="hidden" name="form[slideshow][order]" id="form_order" value="" />    
            <fieldset id="fieldset_event_recap_<?php echo get_if_set($form, "fieldset_event_recap_id"); ?>">
                <div class="file_select_modal_results file_input_horizontal_list" id="slideshow_sortable_list">
                    <?php if (count($form["slideshow"]["fieldset_file_data"]) > 0) { ?>
                        <?php foreach ($form["slideshow"]["fieldset_file_data"] as $fieldset_file_data) {?>
                            <div class="file_item_container" id="<?php echo $fieldset_file_data["fieldset_file_data_id"]; ?>">
                                <input type="hidden" version_id="<?php echo get_if_set($form,"version_id"); ?>" name="form[slideshow][fieldset_file_data][<?php echo $fieldset_file_data["fieldset_file_data_id"]; ?>]" value="<?php echo $fieldset_file_data["fieldset_file_data_id"]; ?>" class="added_fieldset_file_data" <?php echo " base_query='".get_if_set($this->model->get_fieldset_parameters_array(get_if_set($form, "fieldset_event_recap_id")),"base_query")."'"; ?> />
                                <div style="clear:both"></div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <div class="empty_list_message">
                            <p>There are no slides in this slideshow.  Click "Add Slide" to add the first slide.</p>
                            <p>&nbsp;</p>
                            <p>&nbsp;</p>
                        </div>
                    <?php } ?>
                </div>                    
                <a class="btn btn-success add_slideshow" style="width:466px;" href="#">Add Slide</a>
            </fieldset>    
        <fieldset>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?php echo site_url("/fieldset_event_recap/form/" . get_if_set($form, "fieldset_event_recap_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
        loadCSS("/application/fieldsets/fieldset_event_recap/public/slideshow.css");
        loadCSS("/application/fieldsets/fieldset_main_content/public/redactor/redactor.css");
        loadCSS("/application/fieldsets/fieldset_file/public/css/file.css");
        head.js(
            {file_input: "/application/fieldsets/fieldset_file/public/js/file_input.js"},
            {redactor: "/application/fieldsets/fieldset_main_content/public/redactor/redactor.js"}
        );           
        head.ready(function() {
            $("textarea#description").redactor({ imageGetJson: "<?php echo site_url("/fieldset_summary/get_images/" . get_if_set($form, "version_id")); ?>" });        
            set_file_input_proccess();
        });
        
    function set_file_input_proccess(){
        
        function after_input_value_change($input){
            $input.parents(".file_item_container").attr("id",$input.val());
            $input.attr("name","form[slideshow][fieldset_file_data]["+ $input.val() +"]");
            $("#slideshow_sortable_list").sortable("refresh"); 
            $('#form_order').val($("#slideshow_sortable_list").sortable('toArray').toString());            
        }
        
        $("input.added_fieldset_file_data").file_input({after_input_value_change:function($input){
                after_input_value_change($input);
        },after_remove:function($input){
            $input.parents(".file_item_container").fadeOut(200,function(){$(this).remove()});
        }});
        $("a.add_slideshow").click(function(event){
            $("div#slideshow_sortable_list div.empty_list_message").remove();
            var $new_file_input = $("<input type='hidden' name='form[slideshow][fieldset_file_data][]' value='' class='added_fieldset_file_data' version_id='<?php echo get_if_set($form,"version_id"); ?>' <?php echo " base_query='".get_if_set($this->model->get_fieldset_parameters_array(get_if_set($form, "fieldset_event_recap_id")),"base_query")."'"; ?> />");
            var $new_div = $("<div class='file_item_container'></div>");
            $new_div.append($new_file_input);
            $new_file_input.file_input({after_input_value_change:function($input){
                after_input_value_change($input)
            },after_remove:function($input){
                $input.parents(".file_item_container").fadeOut(200,function(){$(this).remove()});
            }});
            $new_div.append("<div style='clear:both'></div>");
            $("div#slideshow_sortable_list").append($new_div);
            event.preventDefault();
        });        
    }
    
    $( "#slideshow_sortable_list").sortable({
            tolerance: "pointer",
            containment: "#fieldset_event_recap_<?php echo get_if_set($form, "fieldset_event_recap_id"); ?>",
            revert: 180,
            update: function(event, ui) {
                $('#form_order').val($("#slideshow_sortable_list").sortable('toArray').toString());
             }
    });
    $("#slideshow_sortable_list, #slideshow_sortable_list #file_item_container" ).disableSelection();        
    $('#form_order').val($("#slideshow_sortable_list").sortable('toArray').toString());        
</script>