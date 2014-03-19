
        
            <div id="modal_work_area">    
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Content Type:</h3>
                </div>
                <div class="modal-body" style="padding-top: 0px">
                    <?php if(isset($validation["message"])){ ?>
                    <div id="modal-message-holder" style="margin-top:4px;">
                        <div z-index="99999" class="alert alert-<?php echo $validation["message"]["type"]; ?>" style="margin-bottom:0;"><a class="close" data-dismiss="alert" href="#">×</a><?php echo $validation["message"]["text"]; ?></div>
                    </div>
                    <?php } ?>                    
                    <div class="content_type_data_modal_form" style="padding-top: 15px">
                        <form class="form-horizontal ajax" action="<?php echo site_url("asterisk_content_type/content_type_form"); ?>" method="post" id="content_type_data_form">
                            <input type="hidden" name="form[content_type_id]" value="<?php echo get_if_set($form, "content_type_id") ?>"/>
                            <fieldset> 
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
                            </fieldset>
                            </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary modal_action_form_save">Save</button>
                    <a href="#" class="btn modal_action_form_cancel" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        