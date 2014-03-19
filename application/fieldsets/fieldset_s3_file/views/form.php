<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/s3_file/". get_if_set($form, "fieldset_s3_file_id")); ?>">&times;</a>
        <h1>S3 File</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_s3_file/form/" . get_if_set($form, "fieldset_s3_file_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_s3_file_id]" value="<?php echo get_if_set($form, "fieldset_s3_file_id"); ?>" />
        <fieldset>
                <?php 
                $filename_value = get_if_set($form, "filename");
                $options = array();
                if($filename_value != ""){
                    $options[$filename_value] = $filename_value;
                }else{
                    $options[""] = "No File";
                }
                form_field_select( array(
                    "label" => "Filename",
                    "name" => "form[filename]",
                    "id" => "filename",
                    "value" => $filename_value,
                    "options" => $options,
                    "control_group_class" => get_if_set($validation, "fields>filename>message>type"),
                    "help_inline" => get_if_set($validation, "fields>filename>message>text"),
                    "source" => site_url("/fieldset_s3_file/get_files/".get_if_set($form, "fieldset_s3_file_id")),
                    "select_class" => "s3_file_searcher"
                    )); ?>            
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_s3_file/form/".get_if_set($form, "fieldset_s3_file_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
    jQuery(function(){
        
        head.js(
            {s3_files:"/application/fieldsets/fieldset_s3_file/public/js/s3_files.js"}
        );
        head.ready("s3_files", function() {
            $("select.s3_file_searcher").getS3Files();
        });    
    });
</script>
