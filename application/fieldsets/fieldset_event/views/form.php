<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/event/" . get_if_set($form, "fieldset_event_id")); ?>">&times;</a>
        <h1>Event</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_event/form/" . get_if_set($form, "fieldset_event_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_event_id]" value="<?php echo get_if_set($form, "fieldset_event_id"); ?>" />

        <fieldset>
            <?php
            form_field_input(array(
                "label" => "Title",
                "name" => "form[title]",
                "id" => "title",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "title"),
                "control_group_class" => get_if_set($validation, "fields>title>message>type"),
                "help_inline" => get_if_set($validation, "fields>title>message>text")
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
                "label" => "Primary Image",
                "name" => "form[primary_image]",
                "id" => "primary_image",
                "type" => "hidden",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "primary_image"),
                "control_group_class" => get_if_set($validation, "fields>primary_image>message>type"),
                "help_inline" => get_if_set($validation, "fields>primary_image>message>text"),
                "extra_attr" => "version_id='".get_if_set($form,"version_id")."'"." base_query='".get_if_set($this->model->get_fieldset_parameters_array(get_if_set($form, "fieldset_event_id")),"primary_image>base_query")."'"
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Location",
                "name" => "form[location]",
                "id" => "location",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "location"),
                "control_group_class" => get_if_set($validation, "fields>location>message>type"),
                "help_inline" => get_if_set($validation, "fields>location>message>text")
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Cost",
                "name" => "form[cost]",
                "id" => "cost",
                "type" => "text",
                "input_class" => "input-medium",
                "value" => get_if_set($form, "cost"),
                "control_group_class" => get_if_set($validation, "fields>cost>message>type"),
                "help_inline" => get_if_set($validation, "fields>cost>message>text")
            ));
            ?>            
            <?php
            form_field_textarea(array(
                "label" => "Primary Text",
                "name" => "form[primary_text]",
                "id" => "primary_text",
                "value" => get_if_set($form, "primary_text"),
                "control_group_class" => get_if_set($validation, "fields>primary_text>message>type"),
                "help_inline" => get_if_set($validation, "fields>primary_text>message>text")
            ));
            ?>                         
            <?php
            form_field_input(array(
                "label" => "Primary Link",
                "name" => "form[primary_link]",
                "id" => "primary_link",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "primary_link"),
                "control_group_class" => get_if_set($validation, "fields>primary_link>message>type"),
                "help_inline" => get_if_set($validation, "fields>primary_link>message>text")
            ));
            ?>
        </fieldset>
        <fieldset>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?php echo site_url("/fieldset_event/form/" . get_if_set($form, "fieldset_event_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
        loadCSS("/application/fieldsets/fieldset_main_content/public/redactor/redactor.css");
        loadCSS("/application/fieldsets/fieldset_file/public/css/file.css");
        head.js(
            {file_input: "/application/fieldsets/fieldset_file/public/js/file_input.js"},
            {redactor: "/application/fieldsets/fieldset_main_content/public/redactor/redactor.js"}
        );
        head.ready(function() {
            $("#primary_image").file_input();
            $("textarea#description").redactor({ imageGetJson: "<?php echo site_url("/fieldset_summary/get_images/" . get_if_set($form, "version_id")); ?>" });        
            $("textarea#primary_text").redactor({ imageGetJson: "<?php echo site_url("/fieldset_summary/get_images/" . get_if_set($form, "version_id")); ?>" });        
        });
</script>