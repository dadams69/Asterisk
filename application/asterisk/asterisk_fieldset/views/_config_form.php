
        
            <div id="modal_work_area">    
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Fieldset Config:</h3>
                </div>
                <div class="modal-body" style="padding-top: 0px">
                    <?php if(isset($validation["message"])){ ?>
                    <div id="modal-message-holder" style="margin-top:4px;">
                        <div z-index="99999" class="alert alert-<?php echo $validation["message"]["type"]; ?>" style="margin-bottom:0;"><a class="close" data-dismiss="alert" href="#">×</a><?php echo $validation["message"]["text"]; ?></div>
                    </div>
                    <?php } ?>                    
                    <div class="config_data_modal_form" style="padding-top: 15px">
                        <form class="form-horizontal ajax" action="<?php echo site_url("asterisk_fieldset/config_form"); ?>" method="post" id="config_data_form">
                            <input type="hidden" name="form[fieldset_id]" value="<?php echo get_if_set($form, "fieldset_id") ?>"/>
                            <input type="hidden" name="form[fieldset_config_id]" value="<?php echo get_if_set($form, "fieldset_config_id") ?>"/>
                            <fieldset> 
                                <?php
                                form_field_input(array(
                                    "label" => "Key",
                                    "name" => "form[key]",
                                    "id" => "key",
                                    "type" => "text",
                                    "input_class" => "input-xlarge",
                                    "value" => get_if_set($form, "key"),
                                    "control_group_class" => get_if_set($validation, "fields>key>message>type"),
                                    "help_inline" => get_if_set($validation, "fields>key>message>text")
                                ));
                                ?>
                                <?php
                                form_field_input(array(
                                    "label" => "Name",
                                    "name" => "form[name]",
                                    "id" => "name",
                                    "type" => "text",
                                    "input_class" => "input-xlarge",
                                    "value" => get_if_set($form, "name"),
                                    "control_group_class" => get_if_set($validation, "fields>name>message>type"),
                                    "help_inline" => get_if_set($validation, "fields>name>message>text")
                                ));
                                ?>
                            <?php form_field_textarea( array(
                                    "label" => "Parameters",
                                    "name" => "form[parameters]",
                                    "input_class" => "span4", 
                                    "id" => "parameters",
                                    "value" => get_if_set($form, "parameters"), 
                                    "control_group_class" => get_if_set($validation, "fields>parameters>message>type"),
                                    "help_inline" => get_if_set($validation, "fields>parameters>message>text"),
                                    "rows" => "5"
                                    ));  ?>                                                             
                            </fieldset>
                            </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary modal_action_form_save">Save</button>
                    <a href="#" class="btn modal_action_form_cancel" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        