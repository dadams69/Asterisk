<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/tags/". get_if_set($form, "fieldset_tags_id")); ?>">&times;</a>
        <h1>Tags</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_tags/form/" . get_if_set($form, "fieldset_tags_id")); ?>" method="post" id="form_fieldset_tags_<?php echo get_if_set($form, "fieldset_tags_id"); ?>">                     
        <input type="hidden" name="form[fieldset_tags_id]" value="<?php echo get_if_set($form, "fieldset_tags_id"); ?>" />
            <?php foreach($group_keys as $group_key_key => $group_key_legend){ ?>
            <fieldset id="fieldset_tags_<?php echo get_if_set($form, "fieldset_tags_id")."_".$group_key_key; ?>">
                <legend><?php echo $group_key_legend; ?></legend>
                <!-- Existing On Load -->

                <?php 
                if (isset($form["tag"][$group_key_key])) { 
                foreach ($form["tag"][$group_key_key]["existing"] as $tag_id) {?>
                    <div class='tag_container'>
                        <input type="hidden" name="form[tags][<?php echo $group_key_key ?>][existing][<?php echo $tag_id; ?>]" value="<?php echo $tag_id; ?>" class="existing_tag"/>
                        <a href='#' class='tag_text btn' target='_blank'><?php echo $form["tag"][$group_key_key]["existing_data"][$tag_id]["tag_name"]; ?></a><a class='tag_remove btn btn-warning' href='#' title='Remove' class='delete_link'> <i class="icon-remove icon-white"></i></a>
                    </div>
                <?php }
                } ?>
                
                <input type="text" class="tag_autocomplete" search_url="/fieldset_tags/find_tags/<?php echo $group_key_key ?>/" />

            </fieldset>
            <?php } ?>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_tags/form/".get_if_set($form, "fieldset_tags_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
    <div id="tag_modal"></div>
</section>

<script type="text/x-jquery-tmpl" class="existing_tag_template">
    <div class='tag_container'>
        <input type="hidden" name="form[tags][${group_key}][existing][${tag_id}]" value="${tag_id}" class="existing_tag" />
        <a href='#' class='tag_text btn' target='_blank'>${name}</a><a class='tag_remove btn btn-warning' href='#' title='Remove' class='delete_link'> <i class="icon-remove icon-white"></i></a>
    </div>
</script>
<script type="text/x-jquery-tmpl" class="new_tag_template">
    <div class='tag_container'>
        <input type="hidden" name="form[tags][${group_key}][new][]" value="${name}" class="new_tag"/>
        <a href='#' class='tag_text btn btn-warning' target='_blank'>${name}</a><a class='tag_remove btn btn-warning' href='#' title='Remove' class='delete_link'> <i class="icon-remove icon-white"></i></a>
    </div>
</script>
    
<?php 
foreach($group_keys as $group_key_key => $group_key_legend){ ?>
     
<?php } ?>


<script>
    jQuery(function(){
        loadCSS("/application/fieldsets/fieldset_tags/public/tags.css");
        head.js(
            {tag_search: "/application/fieldsets/fieldset_tags/public/tags_search.js"},
            {jquery_tmpl: "/application/asterisk/asterisk_common/public/js/jquery.tmpl.js"}
        );
        head.ready(function() {
            $(".tag_autocomplete").tags_search({});
            $("form#form_fieldset_tags_<?php echo get_if_set($form, "fieldset_tags_id"); ?>").on("click",".tag_remove",function(event){
                $(this).parents(".tag_container").fadeIn(function(){
                    $(this).remove();
                });
                event.preventDefault();
            });            
            $("form#form_fieldset_tags_<?php echo get_if_set($form, "fieldset_tags_id"); ?>").on("click","a.tag_text",function(event){
                var $tag_text = $(this);
                if($tag_text.parent().find("input.existing_tag").length > 0){
                    var $tag_input_id = $tag_text.parent().find("input.existing_tag");
                    var $modal_placeholder = $("div#tag_modal");
                    $("#main_container #message-holder div").fadeOut();                            
                    $("#modal-message-holder div").fadeOut();                                    
                    $.post("/fieldset_tags/fieldset_tags_data_form/"+$tag_input_id.val(),function(data){
                        $modal_placeholder.html("");
                        $modal_placeholder.html(data.content.modal);
                        var $modal = $modal_placeholder.find("#tag_form_modal").modal();
                        
                        // ADD CANCEL HANDLER
                        $modal_placeholder.off("click","a.modal_action_form_cancel");
                        $modal_placeholder.on("click","a.modal_action_form_cancel", function (event){
                            $modal_placeholder.find("#modal_work_area").html("");
                            $modal.modal("hide");
                            $("#main_container #message-holder div").fadeOut();                            
                            $("#modal-message-holder div").fadeOut();                
                            event.preventDefault();
                        });

                        // ADD SAVE HANDLER
                        $modal_placeholder.off("click","button.modal_action_form_save");
                        $modal_placeholder.on("click","button.modal_action_form_save", function (event){
                            $.post($("form#tag_data_form").attr("action"),$("form#tag_data_form").serialize(),function(data){
                                if(data.content.modal == ""){
                                    $tag_text.html(data.tag_name);
                                    $modal.modal("hide");
                                    if (typeof data.message != 'undefined') {
                                        $("#main_container #message-holder").html('<div z-index="99999" class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                                    } else {
                                        $("#main_container #message-holder div").fadeOut();                
                                    }                                                              
                                }else{
                                    $modal.find("#modal_work_area").html(data.content.modal);
                                    if (typeof data.message != 'undefined') {
                                        $("#modal-message-holder").html('<div z-index="99999" class="alert alert-' + data.message.type + '"><a class="close" data-dismiss="alert" href="#">×</a>' + data.message.text + '</div>').fadeIn();                
                                    } else {
                                        $("#modal-message-holder div").fadeOut();                
                                    }                                                              
                                }                       
                            },"json");
                            event.preventDefault();
                        });                        
                        
                    },"json");
                }
                event.preventDefault();
            })
        });
    });
</script>