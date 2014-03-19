<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $this->load->view("asterisk_common/head");
        ?>
    </head>

    <body>
        <?php
        $navigation_date = array("active" => "home");
        $this->load->view("asterisk_common/navigation", $navigation_date);
        ?>
        <header id="overview" class="jumbotron subhead">
            <div class="container">
                <h1 id="header_container">
                    Log In
                </h1>
            </div>
        </header>
        <div class="container">
            <div class="inner">
                <div class="span12" id="form-holder">
                    <form class="form-horizontal ajax" action="<?php echo site_url("/asterisk/index"); ?>" method="POST">   
                        <fieldset>
                            <legend>Please enter your email address and password.</legend>                            
                            <?php
                            form_field_input(array(
                                "label" => "Email Address",
                                "name" => "form[email]",
                                "type" => "text",
                                "input_class" => "input-xlarge",
                                "id" => "form_email",
                                "value" => get_if_set($form, "email"),
                                "control_group_class" => get_if_set($validation, "fields>email>message>type"),
                                "help_inline" => get_if_set($validation, "fields>email>message>text")
                            ));
                            ?> 
                            <?php
                            form_field_input(array(
                                "label" => "Password",
                                "name" => "form[password]",
                                "type" => "password",
                                "input_class" => "input-xlarge",
                                "id" => "form_password",
                                "value" => get_if_set($form, "password"),
                                "control_group_class" => get_if_set($validation, "fields>password>message>type"),
                                "help_inline" => get_if_set($validation, "fields>password>message>text")
                            ));
                            ?>                                                              
                        </fieldset>
                        <fieldset>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Login</button>
                            </div>
                        </fieldset>
                    </form>
                </div>    
<?php
$this->load->view("asterisk_common/footer");
?>
            </div>
            <!-- /container --> 

<?php
$this->load->view("asterisk_common/foot");
?>

</html>
