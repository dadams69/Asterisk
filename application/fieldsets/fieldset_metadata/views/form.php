<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/metadata/". get_if_set($form, "fieldset_metadata_id")); ?>">&times;</a>
        <h1>Metadata</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_metadata/form/" . get_if_set($form, "fieldset_metadata_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_metadata_id]" value="<?php echo get_if_set($form, "fieldset_metadata_id"); ?>" />
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
                        "style" => "width:80%; height: 100px",
                        "control_group_class" => get_if_set($validation, "fields>description>message>type"),
                        "help_inline" => get_if_set($validation, "fields>description>message>text")
                    ));
                    ?>
                 <?php
                    form_field_textarea(array(
                        "label" => "Keywords",
                        "name" => "form[keywords]",
                        "id" => "keywords",
                        "value" => get_if_set($form, "keywords"),
                        "style" => "width:80%; height: 100px",
                        "control_group_class" => get_if_set($validation, "fields>keywords>message>type"),
                        "help_inline" => get_if_set($validation, "fields>keywords>message>text")
                    ));
                    ?>
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_metadata/form/".get_if_set($form, "fieldset_metadata_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>

