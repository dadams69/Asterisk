<form id="form_file_data" class="form-horizontal" action="<?php echo site_url("/fieldset_file/form_file_data/".$fieldset_file_id."/".$form["fieldset_file_data_id"]); ?>" method="POST">
    <input type="hidden" name="form[fieldset_file_data_id]" value="<?php echo $form["fieldset_file_data_id"]; ?>" />
    <input type="hidden" name="form[previous_filename]" value="<?php echo $form["previous_filename"]; ?>" />
    <input type="hidden" name="form[extension]" value="<?php echo $form["extension"]; ?>" />
    <?php form_field_input( array(
            "label" => "Full Filename",
            "type" => "text",
            "input_class" => "input-xlarge", 
            "id" => "full_filename",
            "value" => get_if_set($form, "filename").".".get_if_set($form, "extension"),
            "extra_attr" => "disabled='disabled'"
            ));  ?>     
    <?php form_field_input( array(
            "label" => "Filename",
            "name" => "form[filename]",
            "type" => "text",
            "input_class" => "input-xlarge", 
            "id" => "filename",
            "value" => get_if_set($form, "filename"), 
            "control_group_class" => get_if_set($validation, "fields>filename>message>type"),
            "help_inline" => get_if_set($validation, "fields>filename>message>text")        
    ));  ?>     
    <?php form_field_input( array(
            "label" => "Title",
            "name" => "form[title]",
            "type" => "text",
            "input_class" => "input-xlarge", 
            "id" => "title",
            "value" => get_if_set($form, "title"), 
            "control_group_class" => get_if_set($validation, "fields>title>message>type"),
            "help_inline" => get_if_set($validation, "fields>title>message>text")        
    ));  ?> 
    <?php form_field_textarea( array(
            "label" => "Description",
            "name" => "form[description]",
            "type" => "text",
            "input_class" => "input-xlarge", 
            "id" => "description",
            "value" => get_if_set($form, "description"), 
            "control_group_class" => get_if_set($validation, "fields>description>message>type"),
            "help_inline" => get_if_set($validation, "fields>description>message>text")
            ));  ?> 
    
    <?php form_field_textarea( array(
            "label" => "Keywords",
            "name" => "form[keywords]",
            "type" => "text",
            "input_class" => "input-xlarge", 
            "id" => "keywords",
            "value" => get_if_set($form, "keywords"), 
            "control_group_class" => get_if_set($validation, "fields>keywords>message>type"),
            "help_inline" => get_if_set($validation, "fields>keywords>message>text")
            ));  ?>
    <?php form_field_input( array(
            "label" => "Credit",
            "name" => "form[credit]",
            "type" => "text",
            "input_class" => "input-xlarge", 
            "id" => "credit",
            "value" => get_if_set($form, "credit"), 
            "control_group_class" => get_if_set($validation, "fields>credit>message>type"),
            "help_inline" => get_if_set($validation, "fields>credit>message>text")
            ));  ?> 
    
</form>