<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/main_content/". get_if_set($form, "fieldset_main_content_id")); ?>">&times;</a>
        <h1>Main Content</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_main_content/form/" . get_if_set($form, "fieldset_main_content_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_main_content_id]" value="<?php echo get_if_set($form, "fieldset_main_content_id"); ?>" />
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
                form_field_input(array(
                    "label" => "Subtitle",
                    "name" => "form[subtitle]",
                    "id" => "subtitle",
                    "type" => "text",
                    "input_class" => "input-xlarge",                    
                    "value" => get_if_set($form, "subtitle"),
                    "control_group_class" => get_if_set($validation, "fields>subtitle>message>type"),
                    "help_inline" => get_if_set($validation, "fields>subtitle>message>text")
                ));
                ?>
                <?php
                form_field_textarea(array(
                    "label" => "Body",
                    "name" => "form[body]",
                    "id" => "body",
                    "value" => get_if_set($form, "body"),
                    "control_group_class" => get_if_set($validation, "fields>body>message>type"),
                    "help_inline" => get_if_set($validation, "fields>body>message>text")
                ));
                ?>
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_main_content/form/".get_if_set($form, "fieldset_main_content_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
        loadCSS("/application/fieldsets/fieldset_main_content/public/redactor/redactor.css");
        head.js(
           {redactor: "/application/fieldsets/fieldset_main_content/public/redactor/redactor.js"}
        );
        head.ready("redactor", function() {
           $("textarea").redactor({ imageGetJson: "<?php echo site_url("/fieldset_main_content/get_images/".get_if_set($form, "version_id")); ?>" });
        }); 
</script>