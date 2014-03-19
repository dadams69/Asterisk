<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/bio/". get_if_set($form, "fieldset_bio_id")); ?>">&times;</a>
        <h1>Bio</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_bio/form/" . get_if_set($form, "fieldset_bio_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_bio_id]" value="<?php echo get_if_set($form, "fieldset_bio_id"); ?>" />
        <fieldset> 
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
                    "label" => "Institution",
                    "name" => "form[institution]",
                    "id" => "institution",
                    "type" => "text",
                    "input_class" => "input-xlarge",
                    "value" => get_if_set($form, "institution"),
                    "control_group_class" => get_if_set($validation, "fields>institution>message>type"),
                    "help_inline" => get_if_set($validation, "fields>institution>message>text")
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
                form_field_input(array(
                    "label" => "Degree Line",
                    "name" => "form[degree_line]",
                    "id" => "degree_line",
                    "type" => "text",
                    "input_class" => "input-xlarge",
                    "value" => get_if_set($form, "degree_line"),
                    "control_group_class" => get_if_set($validation, "fields>degree_line>message>type"),
                    "help_inline" => get_if_set($validation, "fields>degree_line>message>text")
                ));
                ?>
                <?php
                form_field_textarea(array(
                    "label" => "Bio",
                    "name" => "form[bio]",
                    "id" => "bio",
                    "value" => get_if_set($form, "bio"),
                    "control_group_class" => get_if_set($validation, "fields>bio>message>type"),
                    "help_inline" => get_if_set($validation, "fields>bio>message>text")
                ));
                ?>
                <?php
                form_field_input(array(
                    "label" => "Thumbnail",
                    "name" => "form[thumbnail]",
                    "id" => "thumbnail",
                    "type" => "hidden",
                    "input_class" => "input-xlarge",
                    "value" => get_if_set($form, "thumbnail"),
                    "control_group_class" => get_if_set($validation, "fields>thumbnail>message>type"),
                    "help_inline" => get_if_set($validation, "fields>thumbnail>message>text"),
                    "extra_attr" => "version_id='".get_if_set($form,"version_id")."'"." base_query='".get_if_set($this->model->get_fieldset_parameters_array(get_if_set($form, "fieldset_bio_id")),"image_thumbnail>base_query")."'"
                ));
                ?>  
                <?php
                form_field_input(array(
                    "label" => "Full Image",
                    "name" => "form[full_image]",
                    "id" => "full_image",
                    "type" => "hidden",
                    "input_class" => "input-xlarge",
                    "value" => get_if_set($form, "full_image"),
                    "control_group_class" => get_if_set($validation, "fields>full_image>message>type"),
                    "help_inline" => get_if_set($validation, "fields>full_image>message>text"),
                    "extra_attr" => "version_id='".get_if_set($form,"version_id")."'"." base_query='".get_if_set($this->model->get_fieldset_parameters_array(get_if_set($form, "fieldset_bio_id")),"image_large>base_query")."'"
                ));
                ?>
            
                          
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_bio/form/".get_if_set($form, "fieldset_bio_id")); ?>" class="btn">Reset</a>
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
            $("#thumbnail, #full_image").file_input();
           $("textarea#bio").redactor({ imageGetJson: "<?php echo site_url("/fieldset_bio/get_images/".get_if_set($form, "version_id")); ?>" });
        });
</script>