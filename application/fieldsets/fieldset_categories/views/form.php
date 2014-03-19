<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/categories/". get_if_set($form, "fieldset_categories_id")); ?>">&times;</a>
        <h1>Categories
            <a href="#" id="edit_categories_link">
                <i class="icon-pencil"></i>
            </a>        
        </h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_categories/form/" . get_if_set($form, "fieldset_categories_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_categories_id]" value="<?php echo get_if_set($form, "fieldset_categories_id"); ?>" />
        <fieldset >
            <legend><?php echo $group["name"]; ?></legend>
            <?php echo display_category($categories, -1, $form); ?>
        </fieldset>
        <fieldset>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="<?php echo site_url("/fieldset_categories/form/".get_if_set($form, "fieldset_categories_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
    $(".primary_category").click(function(e) {
       if ($(this).attr("asterisk_checked") == "checked") {
           $('.primary_category').removeAttr('checked');
           $('.primary_category').attr('asterisk_checked', 'unchecked');
       } else {
           $('.primary_category').attr('asterisk_checked', 'unchecked');
           $('.primary_category').removeAttr('checked');
           $(this).attr('checked', "checked");
           $(this).attr("asterisk_checked", "checked");
       }
    });
    
    $("a#edit_categories_link").click(function(e){
        $.post("<?php echo site_url("fieldset_categories/categories_form/". get_if_set($form, "fieldset_categories_id")); ?>",function(data){
            $("#work_area").html(data.content.work_area);
        },"json");
        e.preventDefault();
    });
</script>

<?php

function display_category($categories, $id, $form) {
    if (isset($categories[$id])) {
        foreach($categories[$id] as $row) {
        ?>
                <div class="category_group">
                    <div class="category">
                        <div class="input-prepend">
                            <span class="add-on">
                                <input type="radio" class="primary_category" name="form[primary_category]]" value="<?php echo $row["fieldset_categories_data_id"] ?>" 
                                        <?php if ($form["primary_category"] == $row["fieldset_categories_data_id"]) { ?>
                                            checked="checked" asterisk_checked="checked"  
                                        <?php } ?>/>
                                <input type="checkbox" name="form[category][<?php echo $row["fieldset_categories_data_id"] ?>]" value="true" 
                                       <?php if (isset($form["category"][$row["fieldset_categories_data_id"]])) { ?>
                                            checked="checked" asterisk_checked="checked"  
                                        <?php } ?>/>
                            </span>
                            <span class="input-xlarge uneditable-input"><?php echo $row["category_name"]; ?></span>
                        </div>
                    </div>
                    <?php display_category($categories, $row["fieldset_categories_data_id"], $form); ?>       
                </div>
        <?php
        }
    }
}
