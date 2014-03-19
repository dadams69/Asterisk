<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/publishing/". get_if_set($form, "fieldset_publishing_id")); ?>">&times;</a>
        <h1>Publishing</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_publishing/form/" . get_if_set($form, "fieldset_publishing_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_publishing_id]" value="<?php echo get_if_set($form, "fieldset_publishing_id"); ?>" />
        <fieldset> 
                <?php
                form_field_input(array(
                    "label" => "Key",
                    "name" => "form[key]",
                    "id" => "key",
                    "type" => "text",
                    "input_class" => "input-xlarge",
                    "value" => get_if_set($form, "key"),
                    "control_group_class" => get_if_set($validation, "fields>key>message>type"),
                    "help_inline" => get_if_set($validation, "fields>key>message>text")
                ));
                ?>
                <?php
                form_field_input(array(
                    "label" => "Package",
                    "name" => "form[package]",
                    "id" => "package",
                    "type" => "text",
                    "input_class" => "input-xlarge package_input",                    
                    "value" => get_if_set($form, "package"),
                    "control_group_class" => get_if_set($validation, "fields>package>message>type"),
                    "help_inline" => get_if_set($validation, "fields>package>message>text")
                ));
                ?>
                <?php
                form_field_input(array(
                    "label" => "Rank",
                    "name" => "form[rank]",
                    "id" => "rank",
                    "type" => "text",
                    "input_class" => "input-large",
                    "value" => get_if_set($form, "rank"),
                    "control_group_class" => get_if_set($validation, "fields>rank>message>type"),
                    "help_inline" => get_if_set($validation, "fields>rank>message>text")
                ));
                ?>
                <?php 
                form_field_select( array(
                    "label" => "Status",
                    "name" => "form[active]",
                    "id" => "status",
                    "value" => get_if_set($form, "active"),
                    "options" => array("-1" => "Select status...", "1" => "Active", "0" => "Inactive"),
                    "control_group_class" => get_if_set($validation, "fields>active>message>type"),
                    "help_inline" => get_if_set($validation, "fields>active>message>text")
                    )); ?>            
                <div class="control-group <?php echo get_if_set($validation, "fields>publish_date>message>type"); ?>">
                    <label class="control-label" for="publish_date">Publish Date</label>
                    <div class="controls input-append-custom" id="publish_date_container">
                        <input name="form[publish_date]" type="text" class="input-large" id="publish_date" value="<?php echo get_if_set($form, "publish_date"); ?>" placeholder="MM/DD/YYYYY hh:mm:ss" data-format="MM/dd/yyyy hh:mm:ss"/>
                        <span class="add-on">
                            <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                            </i>
                        </span>
                        <?php if (get_if_set($validation, "fields>publish_date>message>text") !="") { ?>
                            <span class="help-inline"><?php echo get_if_set($validation, "fields>publish_date>message>text"); ?></span>
                        <?php } ?>
                    </div>
                </div>            
                <div class="control-group <?php echo get_if_set($validation, "fields>expiration_date>message>type"); ?>">
                    <label class="control-label" for="expiration_date">Expiration Date</label>
                    <div class="controls input-append-custom" id="expiration_date_container">
                        <input name="form[expiration_date]" type="text" class="input-large" id="expiration_date" value="<?php echo get_if_set($form, "expiration_date"); ?>" placeholder="MM/DD/YYYYY hh:mm:ss" data-format="MM/dd/yyyy hh:mm:ss" />
                        <span class="add-on">
                          <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                          </i>
                        </span>                        
                        <?php if (get_if_set($validation, "fields>expiration_date>message>text") !="") { ?>
                            <span class="help-inline"><?php echo get_if_set($validation, "fields>expiration_date>message>text"); ?></span>
                        <?php } ?>
                    </div>
                </div>            
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_publishing/form/".get_if_set($form, "fieldset_publishing_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
    
    loadCSS("/application/fieldsets/fieldset_publishing/public/bootstrap-datetimepicker.min.css");
    head.js(
        {jquery_tmpl: "/application/fieldsets/fieldset_publishing/public/bootstrap-datetimepicker.min.js"}
    );
    head.ready(function() {
       $('#publish_date_container, #expiration_date_container').datetimepicker({
              language: 'en'
        });
        $(".package_input").autocomplete({
            source: "<?php echo site_url("/fieldset_publishing/get_packages"); ?>",
            minLength: 2
        });
    }); 

</script>