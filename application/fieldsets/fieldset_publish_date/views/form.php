<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/publish_date/". get_if_set($form, "fieldset_publish_date_id")); ?>">&times;</a>
        <h1>Publish Dates</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_publish_date/form/" . get_if_set($form, "fieldset_publish_date_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_publish_date_id]" value="<?php echo get_if_set($form, "fieldset_publish_date_id"); ?>" />
            <fieldset id="publish_date_list">
                <ul class="repeatable-element element_container">
                    <!-- Data from Template -->
                </ul>

                <div class="repeatable-element-actions">
                    <a href="" class="btn element_add">Add Publish Date</a>
                </div>

                <script type="text/x-jquery-tmpl" class="element_template">
                    <li>
                        <input type="hidden" name="form[fieldset_publish_date_data][${fieldset_publish_date_data_id}][fieldset_publish_date_data_id]" value="${fieldset_publish_date_data_id}" />
                        <a class="close element_delete">&times;</a>
                        <?php echo form_template_dropdown(array(
                            "template_field"    =>  "type",
                            "name"              =>  'form[fieldset_publish_date_data][${fieldset_publish_date_data_id}][type]',
                            "label"             =>  "Type",
                            "options"           =>  array("PUBLISHED"=>"Published","UPDATED"=>"Updated")
                        )); ?>           
                        <div class="control-group {{if validation.publish_date}}${validation.publish_date.type}{{/if}}">
                            <label class="control-label">Publish Date</label>
                            <div class="controls input-append-custom datetimepicker_container">
                                <input name="form[fieldset_publish_date_data][${fieldset_publish_date_data_id}][publish_date]" type="text" value="${publish_date}" class="input-large" placeholder="MM/DD/YYYYY hh:mm:ss" data-format="MM/dd/yyyy hh:mm:ss"/>
                                <span class="add-on">
                                    <i data-time-icon="icon-time" data-date-icon="icon-calendar">
                                    </i>
                                </span>
                                {{if validation.publish_date}}
                                    <span class="help-inline">${validation.publish_date.message}</span>
                                {{/if}}
                            </div>
                        </div>                                                              
                        <?php echo form_template_textarea(array(
                            "template_field"    =>  "note",
                            "name"              =>  'form[fieldset_publish_date_data][${fieldset_publish_date_data_id}][note]',
                            "label"             =>  "Note",
                            "rows"              =>  "5",
                            "input_class"       =>  "input-xxlarge"
                        )); ?>                         
                    </li>
                </script>
            </fieldset>                                 
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_publish_date/form/".get_if_set($form, "fieldset_publish_date_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
<script>
jQuery(function(){    
    function set_publish_date_templates(){

        var $fieldset = $("#publish_date_list");
        var $template = $fieldset.find(".element_template");
        var $container = $fieldset.find(".element_container");
        var next_element_id = 0;
        var element_list = <?php echo get_json_element_list($form, $validation, "fieldset_publish_date_data") ?>;

        //element_add implementation
        $fieldset.find(".element_add").unbind("click");
        $fieldset.on("click", ".element_add", function(){
            $template.tmpl({
                fieldset_publish_date_data_id: "new_"+next_element_id,    
                type: "PUBLISHED",
                publish_date: "",
                note: "",
                validation: {}
                //specify other default values
            }).appendTo($container);
            $('.datetimepicker_container').datetimepicker({language: 'en'});
            next_element_id++;
            return false;
        });

        //element_delete implementation
        $container.find(".element_delete").unbind("click");
        $container.on("click", "a.element_delete", function(){
            $(this).parents("li").remove();
        });

        //start
        $template.tmpl(element_list).appendTo($container);          
        
        $('.datetimepicker_container').datetimepicker({language: 'en'});
    }
    
    loadCSS("/application/fieldsets/fieldset_publishing/public/bootstrap-datetimepicker.min.css");

    head.js(
        {jquery_tmpl: "/application/asterisk/asterisk_common/public/js/jquery.tmpl.js"},
        {date_time_picker: "/application/fieldsets/fieldset_publishing/public/bootstrap-datetimepicker.min.js"}
    );
    head.ready(function() {
        set_publish_date_templates()
    });

});
</script>