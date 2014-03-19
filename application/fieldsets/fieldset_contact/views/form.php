<section>
    <div class="page-header">
        <a class="close close-actions dialog_confirm" href="<?php echo site_url("asterisk_content/delete_fieldset/contact/". get_if_set($form, "fieldset_contact_id")); ?>">&times;</a>
        <h1>Contact</h1>
    </div>
    <form class="form-horizontal ajax" action="<?php echo site_url("/fieldset_contact/form/" . get_if_set($form, "fieldset_contact_id")); ?>" method="post">                     
        <input type="hidden" name="form[fieldset_contact_id]" value="<?php echo get_if_set($form, "fieldset_contact_id"); ?>" />
        <fieldset>
            <?php
            form_field_input(array(
                "label" => "First Name",
                "name" => "form[first_name]",
                "id" => "first_name",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "first_name"),
                "control_group_class" => get_if_set($validation, "fields>first_name>message>type"),
                "help_inline" => get_if_set($validation, "fields>first_name>message>text")
            ));
            ?>            
            <?php
            form_field_input(array(
                "label" => "Last Name",
                "name" => "form[last_name]",
                "id" => "last_name",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "last_name"),
                "control_group_class" => get_if_set($validation, "fields>last_name>message>type"),
                "help_inline" => get_if_set($validation, "fields>last_name>message>text")
            ));
            ?>            
            <?php
            form_field_input(array(
                "label" => "Address 1",
                "name" => "form[address_1]",
                "id" => "address_1",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "address_1"),
                "control_group_class" => get_if_set($validation, "fields>address_1>message>type"),
                "help_inline" => get_if_set($validation, "fields>address_1>message>text")
            ));
            ?>            
            <?php
            form_field_input(array(
                "label" => "Address 2",
                "name" => "form[address_2]",
                "id" => "address_2",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "address_2"),
                "control_group_class" => get_if_set($validation, "fields>address_2>message>type"),
                "help_inline" => get_if_set($validation, "fields>address_2>message>text")
            ));
            ?>            
            <?php
            form_field_input(array(
                "label" => "Email",
                "name" => "form[email]",
                "id" => "email",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "email"),
                "control_group_class" => get_if_set($validation, "fields>email>message>type"),
                "help_inline" => get_if_set($validation, "fields>email>message>text")
            ));
            ?>            
            <?php
            form_field_input(array(
                "label" => "Phone",
                "name" => "form[phone]",
                "id" => "phone",
                "type" => "text",
                "input_class" => "input-xlarge",
                "value" => get_if_set($form, "phone"),
                "control_group_class" => get_if_set($validation, "fields>phone>message>type"),
                "help_inline" => get_if_set($validation, "fields>phone>message>text")
            ));
            ?>                        
            </fieldset>
            <fieldset>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <a href="<?php echo site_url("/fieldset_contact/form/".get_if_set($form, "fieldset_contact_id")); ?>" class="btn">Reset</a>
            </div>
        </fieldset>
    </form>
</section>
