<div class="btn-toolbar" style="margin-top: 18px;">
    <div class="btn-group">
            <button class="btn dropdown-toggle" data-toggle="dropdown">Select <span class="caret"></span></button>
            <ul class="dropdown-menu">
                    <li><a href="#" class="select-all-action">All</a></li>
                    <li><a href="#" class="unselect-all-action">None</a></li>
                    <li><a href="#" class="toggle-all-action">Toggle</a></li>
            </ul>
    </div>
    <div class="btn-group">
            <button class="btn dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
            <ul class="dropdown-menu">
                    <li><a href="<?php echo site_url("asterisk_content/delete"); ?>" class="multiaction use_dialog">Delete</a></li>
            </ul>
    </div>
    <div class="btn-group">
            <button class="btn dropdown-toggle" data-toggle="dropdown">New Content <span class="caret"></span></button>
            <ul class="dropdown-menu">
                <li><a href="<?php echo site_url("asterisk_content/form"); ?>" class="">Generic</a></li>
                <?php foreach($content_types as $key=>$value) { ?>
                    <li><a href="<?php echo site_url("asterisk_content/form/create/$key"); ?>" class=""><?php echo $value; ?></a></li>
                <?php } ?>
            </ul>
    </div>
</div>
<!-- /btn-group --> 