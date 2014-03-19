<button class="btn btn-mini"><?php echo $content_type_name; ?></button>
<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
<ul class="dropdown-menu">
    <li><a href="/asterisk_content/edit_content_type/<?php echo $content_id; ?>/<?php echo $version_id; ?>/">Generic</a></li>
    <?php foreach ($content_types as $key => $value) { ?>
        <li><a href="/asterisk_content/edit_content_type/<?php echo $content_id; ?>/<?php echo $version_id; ?>/<?php echo $key; ?>"><?php echo $value; ?></a></li>
    <?php } ?>
</ul>
