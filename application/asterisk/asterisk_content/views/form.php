<!DOCTYPE html>
<html lang="en">
    <head>
        <?php
        $this->load->view("asterisk_common/head"); ?>
        
        <script src="<?php echo base_url("application/asterisk/asterisk_common/public/js/head.load.min.js")?>"></script>
    </head>

    <body>
        <?php
        $navigation_date = array("active" => "content");
        $this->load->view("asterisk_common/navigation", $navigation_date);
        ?>        
        <header id="overview" class="jumbotron subhead">
            <div class="container">
                <h1 id="header_container">
                <?php
                    $this->load->view("asterisk_content/form/_header_title", array("content"=>$content));
                ?>                        
                </h1>
                <div class="btn-toolbar" style="margin: 0;">
                    <div class="btn-group" id="content_type_dropdown">
                        <?php
                            $this->load->view("asterisk_content/form/_content_type", array("content_id"=>$content_id,"version"=>$version,"content_types"=>$content_types,"content_type_name"=>$content_type_name));
                        ?>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-mini">Version <?php echo $version->major_version; ?></button>
                        <button class="btn btn-mini dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <?php foreach ($versions->result() as $row) { ?>
                                <li><a href="/asterisk_content/form/<?php echo $content_id; ?>/<?php echo $row->version_id; ?>"><?php echo $row->major_version; ?></a></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <a href="/asterisk_content/copy_version/<?php echo $content_id; ?>/<?php echo $version_id; ?>" class="btn btn-mini dialog_confirm_direct"  confirmation_message="Are you sure that you want to create a new version?" type="button"><i class="icon-plus"></i></a>
                    <a href="/asterisk_content/delete_version/<?php echo $content_id; ?>/<?php echo $version_id; ?>" class="btn btn-mini dialog_confirm_direct" confirmation_message="Are you sure that you want to delete this version?" type="button"><i class="icon-remove"></i></a>
                    <?php $states = array("PRD","STG");
                        foreach($states as $state){
                    ?>        
                    <div class="btn-group version_state_dropdown" id="<?php echo $state; ?>_version_state">
                        <?php
                            $this->load->view("asterisk_content/form/_version_state", array("content_id"=>$content_id,"version"=>$version,"state"=>$state));
                        ?>                                                
                    </div>                    
                    <?php } ?>
                </div>
            </div>
        </header>

        <div class="container" id="main_container">
            <?php
            $this->load->view("asterisk_common/alert_container");
            ?>
            <div class="row">
                <div class="span3 bs-docs-sidebar" id="navigation" style="min-width: 270px;">
                    <?php $this->load->view("asterisk_content/form/_fieldset_navigation"); ?>
                </div>
                <div class="span9 fade_on_change" id="work_area">

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
