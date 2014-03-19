<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
    <h3>Author:</h3>
</div>
<div class="modal-body" id="modal_work_area" style="padding-top: 0px">
   
    <div class="author_list_modal_form">
        <form class="form-horizontal" action="<?php echo site_url("/fieldset_author/fieldset_author_data_form/" . get_if_set($form, "fieldset_author_data_id")); ?>" method="post" id="author_list_form">
            <div id="modal-message-holder" style="margin-top:4px;"></div>
            <input type="hidden" name="form[fieldset_author_data_id]" id="fieldset_author_data_id" value="<?php echo get_if_set($form, "fieldset_author_data_id"); ?>"/>
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
                "label" => "First Name",
                "name" => "form[first_name]",
                "id" => "first_name",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "first_name"),
                "control_group_class" => get_if_set($validation, "fields>first_name>message>type"),
                "help_inline" => get_if_set($validation, "fields>first_name>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Last Name",
                "name" => "form[last_name]",
                "id" => "last_name",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "last_name"),
                "control_group_class" => get_if_set($validation, "fields>last_name>message>type"),
                "help_inline" => get_if_set($validation, "fields>last_name>message>text")
            ));
            ?>                    
            <?php
            form_field_input(array(
                "label" => "Display Name",
                "name" => "form[display_name]",
                "id" => "display_name",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "display_name"),
                "control_group_class" => get_if_set($validation, "fields>display_name>message>type"),
                "help_inline" => get_if_set($validation, "fields>display_name>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Position",
                "name" => "form[position]",
                "id" => "position",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "position"),
                "control_group_class" => get_if_set($validation, "fields>position>message>type"),
                "help_inline" => get_if_set($validation, "fields>position>message>text")
            ));
            ?>
            <?php
            form_field_textarea(array(
                "label" => "Description",
                "name" => "form[description]",
                "id" => "description",
                "value" => get_if_set($form, "description"),
                "input_class" => "input-xlarge",
                "control_group_class" => get_if_set($validation, "fields>description>message>type"),
                "help_inline" => get_if_set($validation, "fields>description>message>text"),
                "rows" => "5"
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
