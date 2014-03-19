<div class="sidenav-container" data-spy="affix" data-offset-top="110">
    <ul class="nav nav-list bs-docs-sidenav fieldset_nav">
        <?php 
        foreach ($fieldset["existing"] as $item) { ?>
        <li <?php if(!$item["is_allowed"]) {?>class="disabled"<?php } ?>><a href="<?php echo $item["url"]; ?>"><i class="icon-chevron-right"></i> <?php echo $item["name"]; ?></a></li>
        <?php } ?>
    </ul>
    <div class="btn-group add_fieldset_container">
        <?php if (count($fieldset["list"]) > 0 ) { ?>
        <button class="btn">Add Fieldset</button>
        <button class="btn dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu fieldset_dropdown">
            <?php foreach ($fieldset["list"] as $row) { ?>
                    <li><a href="/asterisk_content/add_fieldset/<?php echo $row->fieldset_key; ?>/<?php echo $version_id; ?>/<?php echo $row->fieldset_config_id; ?>"><?php echo $row->fieldset_config_name; ?></a></li>
            <?php } ?>
        </ul>
        <?php } ?>
    </div>            
</div>