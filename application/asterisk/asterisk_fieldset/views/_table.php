    <form action="#" id="fieldset_form">
        <table class="table" id="table_fieldset">
            <thead>
                <tr>
                    <th class="id_column">#</th>
                    <th class="key_column">Fieldset Key</th>   
                    <th class="install_column">Installed</th>   
                    <th class="file_column">Files</th>   
                    <th class="schema_column">Schema</th>   
                    <th class="action_column">Actions</th>   
                </tr>
            </thead>    
            
                <?php 
                if (count($fieldsets) == 0) {
                    ?>
                    <tr>
                        <td colspan="6">No Fieldset Listed</td>
                    </tr>
        <?php } else {
            foreach ($fieldsets as $fieldset) {
                ?>
            <tbody class="fieldset_tbody <?php echo ($fieldset["installed"] == true)?"fieldset_tbody_installed":""; ?>">
                    <input type="hidden" class="fieldset_rank" name="form[fieldset][<?php echo $fieldset["fieldset_id"] ?>][rank]" value="" />
                    <tr class="fieldset_row">
                        <td><?php echo $fieldset["fieldset_id"] ?></td>
                        <td><strong><?php echo $fieldset["key"] ?></strong></td>
                        <td>
                            <?php if($fieldset["installed"] == true){ ?>
                                <span class="label label-info">Installed</span>
                            <?php }else{ ?>
                                <span class="label">Not Installed</span>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if($fieldset["files_exist"] == true){ ?>
                                <span class="label label-info">Files Exists</span>
                            <?php }else{ ?>
                                <span class="label">Files Doesn't Exist</span>
                            <?php } ?>                            
                        </td>
                        <td>
                            <?php if($fieldset["schema_exists"] == true){ ?>
                                <span class="label label-info">Schema Exists</span>
                            <?php }else{ ?>
                                <span class="label">Schema Doesn't Exist</span>
                            <?php } ?>                                                        
                        </td>
                        <td>
                            <?php if($fieldset["installed"] != true){ ?>
                            <a class="btn btn-primary btn-mini action_link dialog_confirm" href="<?php echo site_url("asterisk_fieldset/install_fieldset/".$fieldset["key"]); ?>">Install <i class="icon-plus icon-white"></i></a>
                            <?php }else{ ?>
                            <a class="btn btn-danger btn-mini action_link dialog_confirm" href="<?php echo site_url("asterisk_fieldset/uninstall_fieldset/".$fieldset["key"]); ?>">Uninstall <i class="icon-minus icon-white"></i></a>
                            <?php } ?>
                            <?php if($fieldset["schema_exists"] != true){ ?>
                            <a class="btn btn-primary btn-mini action_link dialog_confirm" href="<?php echo site_url("asterisk_fieldset/create_schema/".$fieldset["key"]); ?>">Schema <i class="icon-plus icon-white"></i></a>
                            <?php }else{ ?>
                            <a class="btn btn-danger btn-mini action_link dialog_confirm" href="<?php echo site_url("asterisk_fieldset/delete_schema/".$fieldset["key"]); ?>">Schema <i class="icon-minus icon-white"></i></a>
                            <?php } ?>
                            <?php if($fieldset["installed"] == true){ ?>
                            <a class="btn btn-success btn-mini add_config" fieldset_id="<?php echo $fieldset["fieldset_id"] ?>" fieldset_config_id="" href="<?php echo site_url("asterisk_fieldset"); ?>">Config <i class="icon-plus icon-white"></i></a>
                            <?php }else{ ?>
                            <a class="btn btn-success btn-mini add_config_disabled" disabled="disabled" fieldset_id="<?php echo $fieldset["fieldset_id"] ?>" fieldset_config_id="" href="<?php echo site_url("asterisk_fieldset"); ?>">Config <i class="icon-plus icon-white"></i></a>
                            <?php } ?>
                        </td>
                    </tr>        
                    <?php foreach($fieldset["fieldset_configs"] as $fieldset_config){ ?>
                    <tr class="config_row">
                        <td ><input type="hidden" class="config_rank" name="form[fieldset][<?php echo $fieldset["fieldset_id"] ?>][fieldset_config][<?php echo $fieldset_config["fieldset_config_id"] ?>][rank]" value="" /></td>
                        <td colspan="4">Config Name: <?php echo $fieldset_config["name"] ?> (<?php echo $fieldset_config["key"] ?>)</td>
                        <td>          
                            <a class="btn btn-primary btn-mini edit_config" fieldset_id="<?php echo $fieldset_config["fieldset_id"] ?>" fieldset_config_id="<?php echo $fieldset_config["fieldset_config_id"] ?>">Edit &nbsp;<i class="icon-pencil icon-white"></i></a>
                            <a class="btn btn-danger btn-mini action_link dialog_confirm" href="<?php echo site_url("asterisk_fieldset/delete_config/".$fieldset_config["fieldset_config_id"]); ?>">Delete <i class="icon-minus icon-white"></i></a>                       
                        </td>
                        
                    </tr>
                    <?php } ?>
                </tbody>
            <?php } ?>
        <?php } ?>
            
        </table>    
    </form>
    <div id="config_modal">
        <div id="config_form_modal" class="modal hide fade" style="width:700px;margin-left:-350px;">
        </div>        
    </div>
<script>

$(document).ready(function(){
            $(".modal-backdrop").fadeOut(function(){$(this).remove();});
            function set_fieldset_rank(){
                    var rank = 1;
                    $("#table_fieldset .fieldset_tbody_installed").each(function(){
                        $(this).find("input.fieldset_rank").val(rank);
                        rank++;
                        var config_rank = 1;
                        $(this).find(".config_row").each(function(){
                            $(this).find("input.config_rank").val(config_rank);
                            config_rank++;
                        });
                        
                    });                
                    return true;
            }
            
            $( "#table_fieldset" ).sortable({
                cursorAt: { top: 5 },
                refreshPosition: true,
                opacity: 0.8,
                scroll: true,
                tolerance: "pointer",
                items: ".fieldset_tbody_installed",
                revert: 180,
                scrollSensitivity: 100,
                update: function(event, ui) {
                    if(set_fieldset_rank()){
                        $("form#fieldset_form").attr("action","<?php echo site_url("asterisk_fieldset/save_fieldset_rank"); ?>");
                        $("form#fieldset_form").submit();
                    }
                },
                helper: function(e, tbody){
                    var $originals = tbody.children();
                    var $helper = tbody.clone();
                    $helper.addClass("helper");
                    $helper.find("td").each(function(index_tr)
                    {
                        var $td = $originals.find("td").eq(index_tr);
                        $(this).width($td.width());
                    });
                    $helper.find("a").remove();
                    $helper.find("input").remove();
                    $helper.find(".config_row").remove();
                    $helper.css({"height":"36px"}); 
                    return $helper;
                },
                placeholder: "ui-placeholder",
                'start': function (event, ui) {
                    ui.placeholder.html('<tr><td colspan="6">&nbsp;</td></tr>');
                },
                'stop': function (event, ui){
                }                
            }).disableSelection(); 
            
            $(".fieldset_tbody_installed").sortable({
                refreshPosition: true,
                opacity: 0.8,
                scroll: true,                
                tolerance: "pointer",
                containment: "parent",
                items: ".config_row",
                revert: 180,
                scrollSensitivity: 40,
                update: function(event, ui) {
                    if(set_fieldset_rank()){
                        $("form#fieldset_form").attr("action","<?php echo site_url("asterisk_fieldset/save_fieldset_config_rank"); ?>");
                        $("form#fieldset_form").submit();
                    }
                },
                helper: function(e, tr){
                    var $originals = tr.children();
                    var $helper = tr.clone();
                    $helper.addClass("helper");
                    $helper.find("td").each(function(index_tr)
                    {
                        var $td = $originals.eq(index_tr);
                        $(this).width($td.width());
                    });
                    $helper.find("a").remove();
                    $helper.find("input").remove();
                    return $helper;
                },
                placeholder: "ui-placeholder-config",
                'start': function (event, ui) {
                    ui.placeholder.html('<td colspan="6">&nbsp;</td>');
                }                                            
            }).disableSelection(); 
            
            
            var $modal_placeholder = $("#config_modal");
            $(".add_config_disabled").click(function(event){
                event.preventDefault();
            })
            
            $(".edit_config, .add_config").click(function(event){

                $.post("<?php echo site_url("asterisk_fieldset/config_form"); ?>/"+$(this).attr("fieldset_id") + "/" + $(this).attr("fieldset_config_id"),function(data){
                    if(data.content.config_form_modal){
                        $("#config_form_modal").html(data.content.config_form_modal).modal();
                        $modal_placeholder.off("click","button.modal_action_form_save");
                        $modal_placeholder.on("click","button.modal_action_form_save", function (event){
                            $modal_placeholder.find("form#config_data_form").submit();
                            event.preventDefault();
                        });                            
                    }
                },"json");

                event.preventDefault();
            });

          
});

</script>    