<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $this->load->view("asterisk_common/head");
        ?>
    </head>

    <body>
        <?php
        $navigation_date = array("active" => "admin_user");
        $this->load->view("asterisk_common/navigation", $navigation_date);
        ?>        
        
        <header id="overview" class="jumbotron subhead">
            <div class="container">
                <h1 id="header_container">
                    Admin User
                </h1>
                <p>Please enter the information for your admin profile.</p>
            </div>
        </header>        
        
        
        <div class="container">
            <?php
            $this->load->view("asterisk_common/alert_container");
            ?>
            <div class="row">
                <div class="span12" id="form-holder">
                    <form class="form-horizontal ajax" 
                          action="<?php echo site_url("asterisk_admin_user/form/" . get_if_set($form, "admin_user_id")); ?>" 
                          method="POST"
                          accept-charset="UTF-8">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <td><strong>Admin User Id:</strong></td>
                                    <td><strong>Last Login At:</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <?php if (get_if_set($form,"admin_user_id")!="") { ?>
                                            <?php echo $form["admin_user_id"]; ?>
                                            <input type="hidden" name="form[admin_user_id]" value="<?php echo $form["admin_user_id"]; ?>" />
                                        <?php } else { ?>
                                            New
                                        <?php } ?>                                    
                                    </td>
                                    <td>
                                        
                                        <?php if (get_if_set($form,"last_login_at")!="") {
                                                    $lma = new DateTime($form["last_login_at"]);
                                                    echo $lma->format("m/d/Y H:i:s");
                                            } else { 
                                                echo "-";
                                            } 
                                        ?>                                 
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <fieldset>
                            <legend>General Information</legend>                                                        
                            <?php
                            form_field_input(array(
                                "label" => "Email",
                                "name" => "form[email]",
                                "type" => "text",
                                "input_class" => "input-xlarge",
                                "id" => "form_email",
                                "value" => get_if_set($form, "email"),
                                "control_group_class" => get_if_set($validation, "fields>email>message>type"),
                                "help_inline" => get_if_set($validation, "fields>email>message>text"),
                                "placeholder" => ""
                            ));
                            ?>
                            <?php
                            form_field_input(array(
                                "label" => "Password",
                                "name" => "form[password]",
                                "type" => "password",
                                "input_class" => "input-xlarge",
                                "id" => "form_password",
                                "value" => "",
                                "control_group_class" => get_if_set($validation, "fields>password>message>type"),
                                "help_inline" => get_if_set($validation, "fields>password>message>text"),
                                "placeholder" => ""
                            ));
                            ?>
                            <?php
                            form_field_input(array(
                                "label" => "Confirm Password",
                                "name" => "form[password_confirm]",
                                "type" => "password",
                                "input_class" => "input-xlarge",
                                "id" => "form_password_confirm",
                                "value" => "",
                                "control_group_class" => get_if_set($validation, "fields>password_confirm>message>type"),
                                "help_inline" => get_if_set($validation, "fields>password_confirm>message>text"),
                                "placeholder" => ""
                            ));
                            ?>
                            <?php
                            form_field_input(array(
                                "label" => "First Name",
                                "name" => "form[first_name]",
                                "type" => "text",
                                "input_class" => "input-xlarge",
                                "id" => "form_first_name",
                                "value" => get_if_set($form, "first_name"),
                                "control_group_class" => get_if_set($validation, "fields>first_name>message>type"),
                                "help_inline" => get_if_set($validation, "fields>first_name>message>text"),
                                "placeholder" => ""
                            ));
                            ?>
                            <?php
                            form_field_input(array(
                                "label" => "Last Name",
                                "name" => "form[last_name]",
                                "type" => "text",
                                "input_class" => "input-xlarge",
                                "id" => "form_last_name",
                                "value" => get_if_set($form, "last_name"),
                                "control_group_class" => get_if_set($validation, "fields>last_name>message>type"),
                                "help_inline" => get_if_set($validation, "fields>last_name>message>text"),
                                "placeholder" => ""
                            ));
                            ?>                            
                            <?php                      
                            form_field_select(array(
                                "label" => "Is Super Admin",
                                "name" => "form[super_admin]",
                                "id" => "form_super_admin",
                                "value" => get_if_set($form, "super_admin"),
                                "options" => array("1"=>"Yes","0"=>"No"),
                                "control_group_class" => get_if_set($validation, "fields>super_admin>message>type"),
                                "help_inline" => get_if_set($validation, "fields>super_admin>message>text"),                                
                            ));
                            ?>                                                                           
                            <?php                      
                            form_field_select(array(
                                "label" => "Status",
                                "name" => "form[status]",
                                "id" => "form_status",
                                "value" => get_if_set($form, "status"),
                                "options" => array("ACTIVE"=>"Active","INACTIVE"=>"Inactive"),
                                "control_group_class" => get_if_set($validation, "fields>status>message>type"),
                                "help_inline" => get_if_set($validation, "fields>status>message>text"),                                
                            ));
                            ?>                                                
                        </fieldset>
                        <fieldset>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <a href="<?php echo site_url("asterisk_admin_user/form/" . get_if_set($form, "admin_user_id")); ?>" class="btn">Reset</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>        
        <!-- /container --> 
        <?php
        $this->load->view("asterisk_common/footer");
        ?>
        <?php
        $this->load->view("asterisk_common/foot");
        ?>
        <script src="<?php echo base_url("application/asterisk/asterisk_common/public/js/form.js") ?>"></script>
        
    </body>     
</html>
