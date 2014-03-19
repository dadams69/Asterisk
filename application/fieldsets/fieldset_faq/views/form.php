<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/faq/". get_if_set($form, "fieldset_faq_id")); ?>">&times;</a>
        <h1>F.A.Q.</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_faq/form/" . get_if_set($form, "fieldset_faq_id")); ?>" method="post" id="faq_form">                     
        <input type="hidden" name="form[fieldset_faq_id]" value="<?php echo get_if_set($form, "fieldset_faq_id"); ?>" />
        <input type="hidden" name="form[order]" id="form_order" value="" />    
            <fieldset id="faq_list">
                <div class="repeatable-element element_container" id="faq_sortable_list">
                    <!-- Data from Template -->
                </div>

                <div class="repeatable-element-actions">
                    <a href="" class="btn element_add">Add F.A.Q. Item</a>
                </div>

                <script type="text/x-jquery-tmpl" class="element_template">
                    <div class="item_list" id="${fieldset_faq_data_id}">
                        <input type="hidden" name="form[fieldset_faq_data][${fieldset_faq_data_id}][fieldset_faq_data_id]" value="${fieldset_faq_data_id}" />
                        <a class="close element_delete">&times;</a>
                        <?php echo form_template_textarea(array(
                            "template_field"    =>  "question",
                            "name"              =>  'form[fieldset_faq_data][${fieldset_faq_data_id}][question]',
                            "label"             =>  "Question",
                            "rows"              =>  "5",
                            "input_class"       =>  "input-xxlarge"
                        )); ?>                         
                        <?php echo form_template_textarea(array(
                            "template_field"    =>  "answer",
                            "name"              =>  'form[fieldset_faq_data][${fieldset_faq_data_id}][answer]',
                            "label"             =>  "Answer",
                            "rows"              =>  "5",
                            "input_class"       =>  "input-xxlarge"
                        )); ?>                                                 
                    </div>
                </script>
            </fieldset>                                 
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_faq/form/".get_if_set($form, "fieldset_faq_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
jQuery(function(){    
    
    loadCSS("/application/fieldsets/fieldset_main_content/public/redactor/redactor.css");
    head.js(
        {jquery_tmpl: "/application/asterisk/asterisk_common/public/js/jquery.tmpl.js"},
        {redactor: "/application/fieldsets/fieldset_main_content/public/redactor/redactor.js"}
    );
    head.ready(function() {
        set_faq_templates()
        set_faq_list_sortable();
    });
    
    function set_faq_templates(){

        var $fieldset = $("#faq_list");
        var $template = $fieldset.find(".element_template");
        var $container = $fieldset.find(".element_container");
        var next_element_id = 0;
        var element_list = <?php echo get_json_element_list($form, $validation, "fieldset_faq_data") ?>;

        //element_add implementation
        $fieldset.find(".element_add").unbind("click");
        $fieldset.on("click", ".element_add", function(){
            $template.tmpl({
                fieldset_faq_data_id: "new_"+next_element_id,    
                question: "",
                answer: "",
                validation: {}
                //specify other default values
            }).appendTo($container);
            
            $('#form_order').val($("#faq_sortable_list").sortable('toArray').toString());
            $("textarea").redactor({ imageGetJson: "<?php echo site_url("/fieldset_main_content/get_images/".get_if_set($form, "version_id")); ?>" });
            
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
        $("textarea").redactor({ imageGetJson: "<?php echo site_url("/fieldset_main_content/get_images/".get_if_set($form, "version_id")); ?>" });
    }

    function set_faq_list_sortable() {
        $( "#faq_sortable_list").sortable({
            tolerance: "pointer",
            containment: "#faq_list",
            revert: 180,
            update: function(event, ui) {
                  $('#form_order').val($("#faq_sortable_list").sortable('toArray').toString());
             }
        });
        $('#form_order').val($("#faq_sortable_list").sortable('toArray').toString());
    }
    
    function unset_faq_list_sortable(){
        $( "#faq_sortable_list").sortable("destroy");
    }
    
    $("#faq_form").on("mouseleave","div.redactor_box",function(){
        set_faq_list_sortable();
    });
    $("#faq_form").on("mouseenter","div.redactor_box",function(){
        unset_faq_list_sortable();
    });        
});
</script>