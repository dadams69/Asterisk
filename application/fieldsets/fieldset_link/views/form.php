<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/link/". get_if_set($form, "fieldset_link_id")); ?>">&times;</a>
        <h1>Link</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_link/form/" . get_if_set($form, "fieldset_link_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_link_id]" value="<?php echo get_if_set($form, "fieldset_link_id"); ?>" />
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
                    "label" => "Url",
                    "name" => "form[url]",
                    "id" => "url",
                    "type" => "text",
                    "input_class" => "input-xlarge",
                    "value" => get_if_set($form, "url"),
                    "control_group_class" => get_if_set($validation, "fields>url>message>type"),
                    "help_inline" => get_if_set($validation, "fields>url>message>text")
                ));
                ?>
                <?php
                form_field_input(array(
                    "label" => "Image",
                    "name" => "form[link_image]",
                    "id" => "link_image",
                    "type" => "hidden",
                    "input_class" => "input-xlarge",
                    "value" => get_if_set($form, "link_image"),
                    "control_group_class" => get_if_set($validation, "fields>link_image>message>type"),
                    "help_inline" => get_if_set($validation, "fields>link_image>message>text"),
                    "extra_attr" => "version_id='".get_if_set($form,"version_id")."'"." base_query='".get_if_set($this->model->get_fieldset_parameters_array(get_if_set($form, "fieldset_link_id")),"image>base_query")."'"
                ));
                ?>            
                <?php
                form_field_textarea(array(
                    "label" => "Description",
                    "name" => "form[description]",
                    "id" => "description",
                    "value" => get_if_set($form, "description"),
                    "control_group_class" => get_if_set($validation, "fields>description>message>type"),
                    "help_inline" => get_if_set($validation, "fields>description>message>text"),
                    "rows" => "10",
                    "style" => "width: 600px"
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
                <div class="control-group <?php echo get_if_set($validation, "fields>date>message>type"); ?>">
                    <label class="control-label" for="date">Date</label>
                    <div class="controls input-append-custom" id="date_container">
                        <input name="form[date]" type="text" class="input-large" id="date" value="<?php echo get_if_set($form, "date"); ?>" placeholder="MM/DD/YYYYY hh:mm:ss" data-format="MM/dd/yyyy hh:mm:ss"/>
                        <span class="add-on">
                            <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                            </i>
                        </span>
                        <?php if (get_if_set($validation, "fields>date>message>text") !="") { ?>
                            <span class="help-inline"><?php echo get_if_set($validation, "fields>date>message>text"); ?></span>
                        <?php } ?>
                    </div>
                </div>                        
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_link/form/".get_if_set($form, "fieldset_link_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
        //loadCSS("/application/fieldsets/fieldset_main_content/public/redactor/redactor.css");
        loadCSS("/application/fieldsets/fieldset_file/public/css/file.css");
        loadCSS("/application/fieldsets/fieldset_link/public/bootstrap-datetimepicker.min.css");
        loadCSS("/application/fieldsets/fieldset_main_content/public/bootstrap-wysihtml5/bootstrap-wysihtml5-0.0.2.css");
        
        head.js(
            {file_input: "/application/fieldsets/fieldset_file/public/js/file_input.js"},
          //  {redactor: "/application/fieldsets/fieldset_main_content/public/redactor/redactor.js"},
            {date_time_picker: "/application/fieldsets/fieldset_publishing/public/bootstrap-datetimepicker.min.js"},
            {wysihtml5: "/application/fieldsets/fieldset_main_content/public/bootstrap-wysihtml5/wysihtml5-0.3.0_rc2.js"},
            {bootstrap_wysihtml5: "/application/fieldsets/fieldset_main_content/public/bootstrap-wysihtml5/bootstrap-wysihtml5-0.0.2.js"}
        );
       /* head.ready("redactor", function() {
           $("textarea#description").redactor({ imageGetJson: "<?php echo site_url("/fieldset_bio/get_images/".get_if_set($form, "version_id")); ?>" });
        });*/
        head.ready("file_input", function() {
            $("#link_image").file_input();
        });
        head.ready("date_time_picker", function() {
            $('#date_container').datetimepicker({
                  language: 'en'
            });
        });
        head.ready("wysihtml5", function() {        
            head.ready("bootstrap_wysihtml5", function() {
                $("textarea#description").wysihtml5();
            });
        });
        

</script>