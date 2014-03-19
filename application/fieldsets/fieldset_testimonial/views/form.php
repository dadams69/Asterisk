<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/testimonial/". get_if_set($form, "fieldset_testimonial_id")); ?>">&times;</a>
        <h1>Testimonial</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_testimonial/form/" . get_if_set($form, "fieldset_testimonial_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_testimonial_id]" value="<?php echo get_if_set($form, "fieldset_testimonial_id"); ?>" />
        <fieldset>
            <?php
            form_field_textarea(array(
                "label" => "Testimonial",
                "name" => "form[testimonial]",
                "id" => "testimonial",
                "value" => get_if_set($form, "testimonial"),
                "control_group_class" => get_if_set($validation, "fields>testimonial>message>type"),
                "help_inline" => get_if_set($validation, "fields>testimonial>message>text")
            ));
            ?> 
            <?php
            form_field_input(array(
                "label" => "Source",
                "name" => "form[source]",
                "id" => "source",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "source"),
                "control_group_class" => get_if_set($validation, "fields>source>message>type"),
                "help_inline" => get_if_set($validation, "fields>source>message>text")
            ));
            ?>            
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_testimonial/form/".get_if_set($form, "fieldset_testimonial_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
        loadCSS("/application/fieldsets/fieldset_main_content/public/redactor/redactor.css");
        head.js(
            {redactor: "/application/fieldsets/fieldset_main_content/public/redactor/redactor.js"}
        );
        head.ready(function() {
            $("textarea#testimonial").redactor({ imageGetJson: "<?php echo site_url("/fieldset_testimonial/get_images/" . get_if_set($form, "version_id")); ?>" });        
        });
</script>
