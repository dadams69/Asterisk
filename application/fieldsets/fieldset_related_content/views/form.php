<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/related_content/". get_if_set($form, "fieldset_related_content_id")); ?>">&times;</a>
        <h1>Related Content</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_related_content/form/" . get_if_set($form, "fieldset_related_content_id")); ?>" method="post" id="form_fieldset_related_content_<?php echo get_if_set($form, "fieldset_related_content_id"); ?>">                     
        <input type="hidden" name="form[fieldset_related_content_id]" value="<?php echo get_if_set($form, "fieldset_related_content_id"); ?>" />
        <input type="hidden" name="form[order]" id="form_order" value="" />    
            <fieldset id="fieldset_related_content_<?php echo get_if_set($form, "fieldset_related_content_id"); ?>">
                <div id="related_content_sortable_list">
                <?php 
                    foreach ($form["content"] as $content) {?>
                    <div class='related_content_container' id="<?php echo $content["content_id"]; ?>">
                        <input type="hidden" name="form[content][<?php echo $content["content_id"]; ?>]" id="form_content_<?php echo $content["content_id"]; ?>]" value="<?php echo $content["content_id"]; ?>" class="added_content"/>
                        <a href='<?php echo site_url("asterisk_content/form/".$content["content_id"]); ?>' class='related_content_text btn' target='_blank'><?php echo $content["name"]; ?></a><a class='related_content_remove btn btn-warning' href='#' title='Remove' class='delete_link'> <i class="icon-remove icon-white"></i></a>
                    </div>
                <?php } ?>
                </div>
                <input type="text" class="related_content_autocomplete" search_url="/fieldset_related_content/find_content/" />
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_related_content/form/".get_if_set($form, "fieldset_related_content_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>

<script type="text/x-jquery-tmpl" class="related_content_template">
    <div class='related_content_container' id="${content_id}">
        <input type="hidden" name="form[content][${content_id}]" value="${content_id}" class="added_content"/>
        <a href='<?php echo site_url("asterisk_content/form"); ?>/${content_id}' class='related_content_text btn' target='_blank'>${name}</a><a class='related_content_remove btn btn-warning' href='#' title='Remove' class='delete_link'> <i class="icon-remove icon-white"></i></a>
    </div>
</script>

<script>
    loadCSS("/application/fieldsets/fieldset_related_content/public/related_content.css");

    head.js(
        {jquery_tmpl: "/application/asterisk/asterisk_common/public/js/jquery.tmpl.js"},
        {date_time_picker: "/application/fieldsets/fieldset_related_content/public/related_content_search.js"}
    );
    head.ready(function() {
        $(".related_content_autocomplete").related_content_search({
            container: "#fieldset_related_content_<?php echo get_if_set($form, "fieldset_related_content_id"); ?> #related_content_sortable_list",
            after_select: function(){
                $( "#related_content_sortable_list" ).sortable("refresh"); 
                $('#form_order').val($("#related_content_sortable_list").sortable('toArray').toString());
            }
        });
    });
    
    jQuery(function(){
        $("form#form_fieldset_related_content_<?php echo get_if_set($form, "fieldset_related_content_id"); ?>").on("click",".related_content_remove",function(event){
            $(this).parents(".related_content_container").fadeIn(function(){
                $(this).remove();
            });
            event.preventDefault();
        });
        $( "#related_content_sortable_list" ).sortable({
                tolerance: "pointer",
                containment: "#fieldset_related_content_<?php echo get_if_set($form, "fieldset_related_content_id"); ?>",
                revert: 180,
                update: function(event, ui) {
                      $('#form_order').val($("#related_content_sortable_list").sortable('toArray').toString());
                 }
        });
        $( "#related_content_sortable_list, #related_content_sortable_list #related_content_container" ).disableSelection();        
        $('#form_order').val($("#related_content_sortable_list").sortable('toArray').toString());
    });
    
</script>