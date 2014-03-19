<!DOCTYPE html>
<html lang="en">
<head>
<?php
    $this->load->view("asterisk_common/head"); 
?>    
<style>
            table#table_fieldset{
                min-width: 940px;
            }

            table#table_fieldset tbody  {
                border-top: 1px solid #CCCCCC !important;
                background-color: #EEEEEE;
            }
            
            table#table_fieldset th.id_column{ width: 24px; }
            table#table_fieldset th.key_column{ 
                width: -moz-calc(100% - 745px);
                width: -webkit-calc(100% - 745px);
                width: calc(100% - 745px);            
            }
            table#table_fieldset th.install_column{ width: 138px; } 
            table#table_fieldset th.file_column{ width: 138px; }
            table#table_fieldset th.schema_column{ width: 138px; }  
            table#table_fieldset th.action_column{ width: 227px; } 
    
            table#table_fieldset tr.fieldset_row a.btn{
                width: 58px;
            }
            table#table_fieldset tr.config_row a.btn:first-child{
                margin-left: 76px;
            }
            table#table_fieldset tr.config_row a.btn{
                width: 58px;
            }            
            table#table_fieldset tr.fieldset_row td{
                background-color: #FFFFFF;
            }
            table#table_fieldset tr.config_row td{
                border-top: 1px dashed #DDDDDD;
                background-color: #EEEEEE;
            }   
            table#table_fieldset tbody.helper{
                border: 1px solid #CCCCCC !important;
            }
            table#table_fieldset tbody.helper td{
                border-width: 0px !important;
            }            
            table#table_fieldset tr.helper{
                border: 1px solid #CCCCCC !important;
                background-color: #EEEEEE;
            }            
            table#table_fieldset tr.helper td{
                border-width: 0px;
            }
            .ui-placeholder{
                background-color: #bbbbff !important;
            }
            .ui-placeholder tr{
                height: 50px;
            }
            
            .ui-placeholder-config{
                background-color: #ffffbb;
                height: 50px;
            }                        
            
            .ui-placeholder-config td{
                border-top: 1px dashed #DDDDDD;
            }                
            
            table#table_fieldset span.label{
                width: 130px;
                text-align: center;
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
                        <h2>Fieldset Manager</h2>
                    <!--<p></p>-->
            </div>            
	</div>    
        <div class="row">
            <div class="span12" id="table_container">
        <?php $this->load->view("asterisk_fieldset/_table"); ?>
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
