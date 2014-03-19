
<?php
$image_types = array("png", "jpg", "jpeg", "gif");
$is_image = $file_data && in_array($file_data->extension, $image_types);
?>
<div class="file_partial thumbnail span2">    
    <?php if ($mode == "input" && $file_data) { ?>
        <a class="close remove_file_action" alt="Remove" href="#remove">×</a>
    <?php } ?>
    <?php if ($file_data) { ?>
        <?php if ($is_image) { ?>
            <div class="file_image_holder">
                <img class="file_full" src="/public/files/<?php echo $file_data->fieldset_file_data_id; ?>/<?php echo $file_data->filename; ?>.<?php echo $file_data->extension; ?>">
                <img class="file_thumb" src="/public/files/<?php echo $file_data->fieldset_file_data_id; ?>/<?php echo $file_data->filename; ?>.<?php echo $file_data->extension; ?>">
            </div>
        <?php } else { ?>
            <div class="file_extension_holder">
                <?php echo $file_data->extension; ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <div class="file_extension_holder">
            ...
        </div>
    <?php } ?>
    <div class="file_caption">
        <?php if ($file_data) { ?>
            <strong  class="file_title"><?php echo $file_data->title; ?></strong>
            <p >
                Type: <?php echo $file_data->extension; ?> 
                <?php if ($is_image) { ?> 
                    (<?php echo $file_data->image_size_key; ?>, <?php echo $file_data->image_height; ?> x <?php echo $file_data->image_width; ?>)
            <?php } ?>
            </p>
        <?php } else { ?>
            <strong class="file_title">No File Selected</strong>
        <?php } ?>    
    </div>

    <?php if ($file_data) { ?>      
        <?php if ($mode == "modal_select") { ?>
            <a href="#" class="btn btn-primary select_action" data-file-data-id="<?php echo $file_data->fieldset_file_data_id; ?>" data-file-data-url="/public/files/<?php echo $file_data->fieldset_file_data_id; ?>/<?php echo $file_data->filename; ?>.<?php echo $file_data->extension; ?>">Select</a>
        <?php } else { ?>
            <a href="#" class="btn btn-primary modal_action">Change...</a>
        <?php } ?>
                    <?php } else { ?>
        <a href="#" class="btn btn-primary modal_action">Select...</a>
    <?php } ?>
</div>
