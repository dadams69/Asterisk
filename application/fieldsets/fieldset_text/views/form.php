<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/text/". get_if_set($form, "fieldset_text_id")); ?>">&times;</a>
        <h1>Text</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_text/form/" . get_if_set($form, "fieldset_text_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_text_id]" value="<?php echo get_if_set($form, "fieldset_text_id"); ?>" />
        <fieldset>            
            <?php
            form_field_textarea(array(
                "label" => "Text",
                "name" => "form[text]",
                "id" => "text",
                "value" => get_if_set($form, "text"),
                "rows"              =>  "5",
                "input_class"       =>  "input-xxlarge",                
                "control_group_class" => get_if_set($validation, "fields>text>message>type"),
                "help_inline" => get_if_set($validation, "fields>text>message>text")
            ));
            ?> 
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_text/form/".get_if_set($form, "fieldset_text_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<?php if(get_if_set($this->model->get_fieldset_parameters_array(get_if_set($form, "fieldset_text_id")),"config>text_mode") == "WYSIWYG"){ ?>
<script>
        loadCSS("/application/fieldsets/fieldset_main_content/public/redactor/redactor.css");
        head.js(
            {redactor: "/application/fieldsets/fieldset_main_content/public/redactor/redactor.js"}
        );
        head.ready(function() {
            $("textarea#text").redactor({ imageGetJson: "<?php echo site_url("/fieldset_text/get_images/" . get_if_set($form, "version_id")); ?>" });        
        });
</script>
<?php } ?>