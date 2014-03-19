<?php if($full_view){ ?>
<div id="tag_form_modal" class="modal hide fade" style="width:700px;margin-left:-350px;">
    <div id="modal_work_area">
<?php } ?>        
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Tag:</h3>
        </div>
        <div class="modal-body" style="padding-top: 0px">
            <div class="tag_data_modal_form">
                <form class="form-horizontal" action="<?php echo site_url("/fieldset_tags/fieldset_tags_data_form/" . get_if_set($form, "fieldset_tags_data_id")); ?>" method="post" id="tag_data_form">
                    <div id="modal-message-holder" style="margin-top:4px;"></div>
                    <input type="hidden" name="form[fieldset_tags_data_id]" id="fieldset_tags_data_id" value="<?php echo get_if_set($form, "fieldset_tags_data_id"); ?>"/>
                    <fieldset> 
                    <?php
                    form_field_input(array(
                        "label" => "Key",
                        "name" => "form[key]",
                        "id" => "key",
                        "type" => "text",
                        "input_class" => "input-xlarge",
                        "value" => get_if_set($form, "key"),
                        "control_group_class" => get_if_set($validation, "fields>key>message>type"),
                        "help_inline" => get_if_set($validation, "fields>key>message>text")
                    ));
                    ?>
                    <?php
                    form_field_input(array(
                        "label" => "Tag Name",
                        "name" => "form[tag_name]",
                        "id" => "tag_name",
                        "type" => "text",
                        "input_class" => "input-xlarge",
                        "value" => get_if_set($form, "tag_name"),
                        "control_group_class" => get_if_set($validation, "fields>tag_name>message>type"),
                        "help_inline" => get_if_set($validation, "fields>tag_name>message>text")
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
<?php if($full_view){ ?>                    
    </div>
</div>
<?php } ?>