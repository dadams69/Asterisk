<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/author/". get_if_set($form, "fieldset_author_id")); ?>">&times;</a>
        <h1>Authors
            <a href="#" id="author_modal_link">
                <i class="icon-pencil"></i>
            </a>
        </h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_author/form/" . get_if_set($form, "fieldset_author_id")); ?>" method="post" id="form_fieldset_related_content_<?php echo get_if_set($form, "fieldset_author_id"); ?>">                     
        <input type="hidden" name="form[fieldset_author_id]" value="<?php echo get_if_set($form, "fieldset_author_id"); ?>" />
        <input type="hidden" name="form[order]" id="form_order" value="" />    
            <fieldset id="fieldset_author_<?php echo get_if_set($form, "fieldset_author_id"); ?>">
                
                            <div class="repeatable-element element_container" id="author_sortable_list">
                                <!-- Data from Template -->
                            </div>
                            
                            <div class="repeatable-element-actions">
                                <a href="" class="btn element_add">Add Author</a>
                            </div>
                            <script type="text/x-jquery-tmpl" class="element_template">
                                <div class="item_list" id="${fieldset_author_mapping_id}">
                                    <a class="close element_delete">&times;</a>            
                                    <input type="hidden" name="form[fieldset_author_mapping][${fieldset_author_mapping_id}][fieldset_author_mapping_id]" value="${fieldset_author_mapping_id}" />
                                    <div class="control-group {{if validation.fieldset_author_data_id}}${validation.fieldset_author_data_id.type}{{/if}}">         
                                        <label class="control-label">Author</label>
                                        <div class="controls">             
                                            <input type="hidden" name="form[fieldset_author_mapping][${fieldset_author_mapping_id}][fieldset_author_data_id]" ref_display="form_fieldset_author_mapping_${fieldset_author_mapping_id}_author_data_display_name" value="${fieldset_author_data_id}" class="search_author"/>
                                            <input type="hidden" name="form[fieldset_author_mapping][${fieldset_author_mapping_id}][author_data_display_name]" id="form_fieldset_author_mapping_${fieldset_author_mapping_id}_author_data_display_name" value="${author_data_display_name}" />
                                            {{if validation.fieldset_author_data_id}}
                                                <span class="help-inline">${validation.fieldset_author_data_id.message}</span>
                                            {{/if}}
                                        </div>     
                                    </div>                                    
                                    <?php echo form_template_input(array(
                                        "template_field"    =>  "display_name",
                                        "name"              =>  'form[fieldset_author_mapping][${fieldset_author_mapping_id}][display_name]',
                                        "label"             =>  "Display Name",
                                        "input_class"             =>  "input-xlarge author_display_name"
                                    )); ?>                                                           
                                    <?php echo form_template_input(array(
                                        "template_field"    =>  "position",
                                        "name"              =>  'form[fieldset_author_mapping][${fieldset_author_mapping_id}][position]',
                                        "label"             =>  "Position",
                                        "input_class"             =>  "input-xlarge author_position"
                                    )); ?>                                                           
                                </div>
                            </script>                
            </fieldset>                              
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_author/form/".get_if_set($form, "fieldset_author_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
    <div id="author_modal"></div>
</section>

<script>

        loadCSS("/application/fieldsets/fieldset_author/public/author_list.css");
        head.js(
            {jquery_tmpl: "/application/fieldsets/fieldset_author/public/jquery.tmpl.js"},
            {author_search: "/application/fieldsets/fieldset_author/public/author_search.js"},
            {author_list: "/application/fieldsets/fieldset_author/public/author_list.js"}
        );
        head.ready(function() {
           $("#author_modal_link").author_list();
            set_authors_functionalities();
            set_author_list_sortable();
        }); 
        
        function set_author_list_sortable() {
            $( "#author_sortable_list" ).sortable({
                tolerance: "pointer",
                containment: "#author_sortable_list",
                revert: 180,
                update: function(event, ui) {
                      $('#form_order').val($("#author_sortable_list").sortable('toArray').toString());
                 }
            });
            $('#form_order').val($("#author_sortable_list").sortable('toArray').toString());
        }
        
        function set_authors_functionalities(){
       
            var $fieldset = $("#fieldset_author_<?php echo get_if_set($form, "fieldset_author_id"); ?>");
            var $template = $fieldset.find(".element_template");
            var $container = $fieldset.find(".element_container");
            var next_element_id = 0;
            var element_list = <?php echo get_json_element_list($form, $validation, "fieldset_author_mapping") ?>;

            //element_add implementation
            $fieldset.find(".element_add").unbind("click");
            $fieldset.on("click", ".element_add", function(){
                $template.tmpl({
                    fieldset_author_mapping_id: "new_"+next_element_id,
                    fieldset_author_data_id: "",
                    author_data_display_name: "",
                    display_name: "",
                    position: "",
                    validation: {}
                        //specify other default values
                }).appendTo($container);

                $('#form_order').val($("#author_sortable_list").sortable('toArray').toString());
                $container.find(".search_author").author_search({});

                next_element_id++;
                return false;
            });

            //element_delete implementation
            $container.find(".element_delete").unbind("click");
            $container.on("click", "a.element_delete", function(){
                $(this).parents("div.item_list").remove();
            });

            //start
            $template.tmpl(element_list).appendTo($container);
            $container.find(".search_author").author_search({});
        }
    
</script>