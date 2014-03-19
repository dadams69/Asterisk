<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/summary/" . get_if_set($form, "fieldset_summary_id")); ?>">&times;</a>
        <h1>Summary</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_summary/form/" . get_if_set($form, "fieldset_summary_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_summary_id]" value="<?php echo get_if_set($form, "fieldset_summary_id"); ?>" />

        <fieldset>

            <?php
            form_field_input(array(
                "label" => "Image Size #1",
                "name" => "form[image_size_1]",
                "id" => "image_size_1",
                "type" => "hidden",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "image_size_1"),
                "control_group_class" => get_if_set($validation, "fields>image_size_1>message>type"),
                "help_inline" => get_if_set($validation, "fields>image_size_1>message>text"),
                "extra_attr" => "version_id='".get_if_set($form,"version_id")."'"." base_query='".get_if_set($this->model->get_fieldset_parameters_array(get_if_set($form, "fieldset_summary_id")),"image_size_1>base_query")."'"
            ));
            ?>
            <?php
            form_field_input(array(
                "label" => "Image Size #2",
                "name" => "form[image_size_2]",
                "id" => "image_size_2",
                "type" => "hidden",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "image_size_2"),
                "control_group_class" => get_if_set($validation, "fields>image_size_2>message>type"),
                "help_inline" => get_if_set($validation, "fields>image_size_2>message>text"),
                "extra_attr" => "version_id='".get_if_set($form,"version_id")."'"." base_query='".get_if_set($this->model->get_fieldset_parameters_array(get_if_set($form, "fieldset_summary_id")),"image_size_2>base_query")."'"
            ));
            ?>
            <?php
            form_field_textarea(array(
                "label" => "Short Description",
                "name" => "form[short_description]",
                "id" => "short_description",
                "value" => get_if_set($form, "short_description"),
                "control_group_class" => get_if_set($validation, "fields>short_description>message>type"),
                "help_inline" => get_if_set($validation, "fields>short_description>message>text")
            ));
            ?>     
            <br />
            <br />
            <?php
            form_field_textarea(array(
                "label" => "Quote",
                "name" => "form[quote]",
                "id" => "quote",
                "value" => get_if_set($form, "quote"),
                "control_group_class" => get_if_set($validation, "fields>quote>message>type"),
                "help_inline" => get_if_set($validation, "fields>quote>message>text")
            ));
            ?> 
            <?php
            form_field_input(array(
                "label" => "Quote attribution",
                "name" => "form[quote_attribution]",
                "id" => "quote_attribution",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "quote_attribution"),
                "control_group_class" => get_if_set($validation, "fields>quote_attribution>message>type"),
                "help_inline" => get_if_set($validation, "fields>quote_attribution>message>text")
            ));
            ?>


        </fieldset>
        <fieldset>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?php echo site_url("/fieldset_summary/form/" . get_if_set($form, "fieldset_summary_id")); ?>" class="btn">Reset</a>
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
            $("#image_size_1, #image_size_2").file_input();
            $("textarea#short_description").redactor({ imageGetJson: "<?php echo site_url("/fieldset_summary/get_images/" . get_if_set($form, "version_id")); ?>" });        
            $("textarea#quote").redactor({ imageGetJson: "<?php echo site_url("/fieldset_summary/get_images/" . get_if_set($form, "version_id")); ?>" });        
        });
</script>