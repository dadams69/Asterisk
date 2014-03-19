<button class="btn btn-mini">
    <?php echo ($state == "PRD")?"Production":"Staging"; ?> Version 
    <?php if(isset($version_states[$state])){ ?>    
        <?php echo $version_states[$state] ?>
    <?php }else{ ?>
        None
    <?php } ?>
</button>
<button class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
<ul class="dropdown-menu">
    <?php foreach ($versions->result() as $row) { ?>
        <li><a href="/asterisk_content/set_version_state/<?php echo $row->version_id."/".$state; ?>/true"><?php echo $row->major_version; ?></a></li>
    <?php } ?>
    <li class="divider"></li>
    <li><a href="/asterisk_content/set_version_state/<?php echo $row->version_id."/".$state; ?>/none">None</a></li>
</ul>
                    