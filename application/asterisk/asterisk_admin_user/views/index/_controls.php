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
                    <li><a href="<?php echo site_url("asterisk_admin_user/set_status/active/"); ?>" class="multiaction">Set Status Active</a></li>
                    <li><a href="<?php echo site_url("asterisk_admin_user/set_status/inactive/"); ?>" class="multiaction">Set Status Inactive</a></li>
                    <li class="divider"></li>                
                    <li><a href="<?php echo site_url("asterisk_admin_user/delete"); ?>" class="multiaction use_dialog">Delete</a></li>
            </ul>
    </div>
    <div class="btn-group"> <a class="btn" href="<?php echo site_url("asterisk_admin_user/form"); ?>">Create New Admin User</a> </div>
</div>
<!-- /btn-group --> 