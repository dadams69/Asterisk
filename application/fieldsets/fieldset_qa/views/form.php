<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/qa/". get_if_set($form, "fieldset_qa_id")); ?>">&times;</a>
        <h1>Question/Answer</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_qa/form/" . get_if_set($form, "fieldset_qa_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_qa_id]" value="<?php echo get_if_set($form, "fieldset_qa_id"); ?>" />
        <fieldset>
                <?php
                    form_field_textarea(array(
                        "label" => "Question",
                        "name" => "form[question]",
                        "id" => "question",
                        "value" => get_if_set($form, "question"),
                        "style" => "width:80%; height: 100px",
                        "control_group_class" => get_if_set($validation, "fields>question>message>type"),
                        "help_inline" => get_if_set($validation, "fields>question>message>text")
                    ));
                    ?>
                <?php
                    form_field_textarea(array(
                        "label" => "Answer",
                        "name" => "form[answer]",
                        "id" => "answer",
                        "value" => get_if_set($form, "answer"),
                        "style" => "width:80%; height: 100px",
                        "control_group_class" => get_if_set($validation, "fields>answer>message>type"),
                        "help_inline" => get_if_set($validation, "fields>answer>message>text")
                    ));
                    ?>
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_qa/form/".get_if_set($form, "fieldset_qa_id")); ?>" class="btn">Reset</a>
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
