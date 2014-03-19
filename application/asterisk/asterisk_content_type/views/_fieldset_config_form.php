
        
            <div id="modal_work_area">    
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h3>Fieldset Config List:</h3>
                </div>
                <div class="modal-body" style="padding-top: 0px">
                    <?php if(isset($validation["message"])){ ?>
                    <div id="modal-message-holder" style="margin-top:4px;">
                        <div z-index="99999" class="alert alert-<?php echo $validation["message"]["type"]; ?>" style="margin-bottom:0;"><a class="close" data-dismiss="alert" href="#">×</a><?php echo $validation["message"]["text"]; ?></div>
                    </div>
                    <?php } ?>                    
                    <div class="fieldset_config_data_modal_form" style="padding-top: 15px">
                        <form class="form-horizontal ajax" action="<?php echo site_url("asterisk_content_type/fieldset_config_form"); ?>" method="post" id="fieldset_config_data_form">
                            <input type="hidden" name="form[content_type_id]" value="<?php echo get_if_set($form, "content_type_id") ?>"/>
                            <table class="table" id="table_fieldset_config_list">
                                <thead>
                                    <tr>
                                        <th style="width: 5%"></th>
                                        <th style="width: 10%">#</th>
                                        <th style="width: 85%">Fieldset Config Name (Fieldset Name)</th>   
                                    </tr>
                                </thead>    

                                    <?php 
                                    if (!get_if_set($form,"fieldset_configs",null) || count($form["fieldset_configs"]) == 0) {
                                        ?>
                                        <tr>
                                            <td colspan="3">No Fieldset Config Listed</td>
                                        </tr>
                            <?php } else {
                                foreach ($form["fieldset_configs"] as $fieldset_config) {
                                    ?>
                                    <tr>
                                            <td>
                                                <input type="checkbox" class="add_fieldset_config_checkbox" value="<?php echo get_if_set($fieldset_config,"fieldset_config_id"); ?>" name="form[add_fieldset_config][<?php echo get_if_set($fieldset_config,"fieldset_config_id"); ?>]" <?php echo (get_if_set($form,"add_fieldset_config_id>".get_if_set($fieldset_config,"fieldset_config_id"),null))?"checked='checked'":""; ?> />
                                                <input type="hidden" name="form[fieldset_configs][<?php echo get_if_set($fieldset_config,"fieldset_config_id"); ?>][fieldset_config_id]" value="<?php echo get_if_set($fieldset_config,"fieldset_config_id"); ?>" />
                                                <input type="hidden" name="form[fieldset_configs][<?php echo get_if_set($fieldset_config,"fieldset_config_id"); ?>][name]" value="<?php echo get_if_set($fieldset_config,"name"); ?>" />
                                                <input type="hidden" name="form[fieldset_configs][<?php echo get_if_set($fieldset_config,"fieldset_config_id"); ?>][fieldset_name]" value="<?php echo get_if_set($fieldset_config,"fieldset_name"); ?>" />
                                            </td>
                                            <td><?php echo $fieldset_config["fieldset_config_id"] ?></td>
                                            <td>
                                                <?php echo $fieldset_config["name"] ?> (<?php echo $fieldset_config["fieldset_name"] ?>)
                                            </td>
                                        </tr>        

                                <?php } ?>
                            <?php } ?>

                            </table>                                
                            </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary modal_action_form_save">Add</button>
                    <a href="#" class="btn modal_action_form_cancel" data-dismiss="modal">Cancel</a>
                </div>
            </div>
        