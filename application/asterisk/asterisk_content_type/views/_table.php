    <form action="#" id="content_type_form">
        <table class="table" id="table_content_type">
            <thead>
                <tr>
                    <th class="id_column">#</th>
                    <th class="name_column">Name</th>   
                    <th class="action_column">Actions</th>   
                </tr>
            </thead>    
                <?php 
                if (count($content_types) == 0) {
                    ?>
                    <tr>
                        <td colspan="3">No Content Type Listed</td>
                    </tr>
        <?php } else {
            foreach ($content_types as $content_type) {
                ?>
            <tbody class="content_type_tbody content_type_tbody">
                    <input type="hidden" class="content_type_rank" name="form[content_type][<?php echo $content_type["content_type_id"] ?>][rank]" value="" />
                    <tr class="content_type_row">
                        <td><?php echo $content_type["content_type_id"] ?></td>
                        <td><strong><?php echo $content_type["name"] ?></strong></td>
                        <td>
                            <a class="btn btn-danger btn-mini action_link dialog_confirm" href="<?php echo site_url("asterisk_content_type/delete/".$content_type["content_type_id"]); ?>">Delete <i class="icon-minus icon-white"></i></a>                       
                            <a class="btn btn-primary btn-mini edit_content_type" content_type_id="<?php echo $content_type["content_type_id"] ?>">Edit &nbsp;<i class="icon-pencil icon-white"></i></a>
                            <a class="btn btn-success btn-mini add_content_type_fieldset_config" content_type_id="<?php echo $content_type["content_type_id"] ?>">Add Config &nbsp;<i class="icon-plus icon-white"></i></a>
                        </td>
                    </tr>        
                    <?php foreach($content_type["content_type_fieldset_configs"] as $content_type_fieldset_config){ ?>  
                    <tr class="fieldset_config_row">
                        <td>
                            <input type="hidden" class="fieldset_config_rank" name="form[content_type][<?php echo $content_type["content_type_id"] ?>][content_type_fieldset_config][<?php echo $content_type_fieldset_config["content_type_fieldset_config_id"] ?>][rank]" value="" />
                        </td>
                        <td>
                            Config Name: <?php echo $content_type_fieldset_config["name"] ?> (<?php echo $content_type_fieldset_config["fieldset_name"] ?>)
                        </td>
                        <td>          
                            <a class="btn btn-danger btn-mini action_link dialog_confirm" href="<?php echo site_url("asterisk_content_type/delete_config/".$content_type_fieldset_config["content_type_fieldset_config_id"]); ?>">Delete <i class="icon-minus icon-white"></i></a>                       
                            <!--<a class="btn btn-success btn-mini see_fieldset_config" fieldset_config_id="<?php echo $fieldset_config["fieldset_id"] ?>" fieldset_config_id="<?php echo $fieldset_config["fieldset_config_id"] ?>">See Details &nbsp;<i class="icon-pencil icon-white"></i></a>-->
                        </td>
                        
                    </tr>
                    <?php } ?>
                </tbody>
            <?php } ?>
        <?php } ?>
            
        </table>    
    </form>
    <div id="modal_placeholder">
        <div id="content_type_form_modal" class="modal hide fade" style="width:700px;margin-left:-350px;">
        </div>        
    </div>
<script>

$(document).ready(function(){
            $(".modal-backdrop").fadeOut(function(){$(this).remove();});
            function set_content_type_rank(){
                    var rank = 1;
                    $("#table_content_type .content_type_tbody").each(function(){
                        $(this).find("input.content_type_rank").val(rank);
                        rank++;
                        var fieldset_config_rank = 1;
                        $(this).find(".fieldset_config_row").each(function(){
                            $(this).find("input.fieldset_config_rank").val(fieldset_config_rank);
                            fieldset_config_rank++;
                        });
                        
                    });                
                    return true;
            }
            $("#table_content_type .content_type_tbody .content_type_row").mousedown(function(){
                $("#table_content_type .content_type_tbody .fieldset_config_row").hide();
            });
            $("#table_content_type .content_type_tbody .content_type_row").mouseup(function(){
                $("#table_content_type .content_type_tbody .fieldset_config_row").slideDown();
            });
            
            $("#table_content_type .content_type_tbody .content_type_row a").mousedown(function(event){
                event.stopPropagation();
            });
            
            $( "#table_content_type" ).sortable({
                cursorAt: { top: 5 },
                refreshPosition: true,
                opacity: 0.8,
                scroll: true,
                tolerance: "pointer",
                items: ".content_type_tbody",
                revert: 180,
                scrollSensitivity: 100,
                update: function(event, ui) {
                    if(set_content_type_rank()){
                        $("form#content_type_form").attr("action","<?php echo site_url("asterisk_content_type/save_content_type_rank"); ?>");
                        $("form#content_type_form").submit();
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
                    $helper.find(".fieldset_config_row").remove();
                    $helper.css({"height":"36px"}); 
                    return $helper;
                },
                placeholder: "ui-placeholder",
                'start': function (event, ui) {
                    ui.placeholder.html('<tr><td colspan="3">&nbsp;</td></tr>');
                },
                'stop': function (event, ui){
                    $("#table_content_type .content_type_tbody .fieldset_config_row").slideDown();
                }
            }).disableSelection(); 
            
            $(".content_type_tbody").sortable({
                refreshPosition: true,
                opacity: 0.8,
                scroll: true,                
                tolerance: "pointer",
                containment: "parent",
                items: ".fieldset_config_row",
                revert: 180,
                scrollSensitivity: 40,
                update: function(event, ui) {
                    if(set_content_type_rank()){
                        $("form#content_type_form").attr("action","<?php echo site_url("asterisk_content_type/save_content_type_fieldset_config_rank"); ?>");
                        $("form#content_type_form").submit();
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
                placeholder: "ui-placeholder-fieldset-config",
                'start': function (event, ui) {
                    ui.placeholder.html('<td colspan="3">&nbsp;</td>');
                }                                            
            }).disableSelection(); 
            
            var $modal_placeholder = $("#modal_placeholder");
            
            $(".edit_content_type, .add_content_type").click(function(event){

                $.post("<?php echo site_url("asterisk_content_type/content_type_form"); ?>/"+$(this).attr("content_type_id"),function(data){
                    if(data.content.content_type_form_modal){
                        $("#content_type_form_modal").html(data.content.content_type_form_modal).modal();
                        $modal_placeholder.off("click","button.modal_action_form_save");
                        $modal_placeholder.on("click","button.modal_action_form_save", function (event){
                            $modal_placeholder.find("form#content_type_data_form").submit();
                            event.preventDefault();
                        });                            
                    }
                },"json");

                event.preventDefault();
            });            
            
            $(".add_content_type_fieldset_config").click(function(event){

                $.post("<?php echo site_url("asterisk_content_type/fieldset_config_form"); ?>/"+$(this).attr("content_type_id"),function(data){
                    if(data.content.content_type_form_modal){
                        $("#content_type_form_modal").html(data.content.content_type_form_modal).modal();
                        $modal_placeholder.off("click","button.modal_action_form_save");
                        $modal_placeholder.on("click","button.modal_action_form_save", function (event){
                            $modal_placeholder.find("form#fieldset_config_data_form").submit();
                            event.preventDefault();
                        });                            
                        
                        $modal_placeholder.off("click",".add_fieldset_config_checkbox");
                        $modal_placeholder.on("click",".add_fieldset_config_checkbox", function (event){
                            event.stopPropagation();
                        });
                        
                        $modal_placeholder.off("click","#table_fieldset_config_list tbody tr");
                        $modal_placeholder.on("click","#table_fieldset_config_list tbody tr", function (event){
                            var $checkbox = $(this).find(".add_fieldset_config_checkbox")
                            if($checkbox.is(":checked")){
                                $checkbox.removeAttr("checked");
                            }else{
                                $checkbox.attr("checked","checked");
                            }
                            event.preventDefault();
                        });                                                    
                    }
                },"json");

                event.preventDefault();
            });                        
          
});

</script>    