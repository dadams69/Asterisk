<!DOCTYPE html>
<html lang="en">
<head>
<?php
    $this->load->view("asterisk_common/head"); 
?>    
<style>
            table#table_content_type{
                min-width: 940px;
            }

            table#table_content_type tbody  {
                border-top: 1px solid #CCCCCC !important;
                background-color: #EEEEEE;
            }
            
            table#table_content_type th.id_column{ width: 24px; }
            table#table_content_type th.name{ 
                width: -moz-calc(100% - 300px);
                width: -webkit-calc(100% - 300px);
                width: calc(100% - 300px);            
            }
            table#table_content_type th.action_column{ width: 276px; } 
    
            table#table_content_type tr.content_type_row a.btn{
                width: 75px;
            }
            table#table_content_type tr.fieldset_config_row a.btn:first-child{
                margin-left: 186px;
            }
            table#table_content_type tr.fieldset_config_row a.btn{
                width: 75px;
            }            
            table#table_content_type tr.content_type_row td{
                background-color: #FFFFFF;
            }
            table#table_content_type tr.fieldset_config_row td{
                border-top: 1px dashed #DDDDDD;
                background-color: #EEEEEE;
            }   
            table#table_content_type tbody.helper{
                border: 1px solid #CCCCCC !important;
            }
            table#table_content_type tbody.helper td{
                border-width: 0px !important;
            }            
            table#table_content_type tr.helper{
                border: 1px solid #CCCCCC !important;
                background-color: #EEEEEE;
            }            
            table#table_content_type tr.helper td{
                border-width: 0px;
            }
            .ui-placeholder{
                background-color: #bbbbff !important;
            }
            .ui-placeholder tr{
                height: 56px;
            }
            
            .ui-placeholder-fieldset-config{
                background-color: #ffffbb;
                height: 56px;
            }                        
            
            .ui-placeholder-fieldset-config td{
                border-top: 1px dashed #DDDDDD;
            }                
            
            table#table_content_type span.label{
                width: 130px;
                text-align: center;
            }
            
            table#table_fieldset_config_list{
                
            }
            
            table#table_fieldset_config_list tbody tr:hover{
                background-color: #ffffbb;
            }            
    </style>    
</head>

<body>
<?php
    $navigation_date = array("active" => "database");
    $this->load->view("asterisk_common/navigation", $navigation_date); 
?>

<div class="container" id="main_container">
	<?php
            $this->load->view("asterisk_common/alert_container"); 
        ?>
	<div class="row">
            <div class="span12">
                        <h2>Content Type Manager</h2>
                    <!--<p></p>-->
            </div>            
	</div>    
	<div class="row">
		<div class="span4">
                    <div class="btn-toolbar" style="margin-top: 18px;">                        
                        <div class="btn-group"> <a class="btn add_content_type" href="#" content_type_id="">Create New Content Type</a> </div>
                    </div>
                    <!-- /btn-group --> 
		</div>
	</div>    
        <div class="row">
            <div class="span12" id="table_container">
                <?php $this->load->view("asterisk_content_type/_table"); ?>
            </div>
        </div>
    
	<?php
        $this->load->view("asterisk_common/footer"); 
        ?>
</div>
<!-- /container --> 

<?php
    $this->load->view("asterisk_common/foot"); 
?>
<script src="<?php echo base_url("application/asterisk/asterisk_common/public/js/form.js") ?>"></script>

</html>
