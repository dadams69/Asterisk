<!DOCTYPE html>
<html lang="en">
<head>
<?php
    $this->load->view("asterisk_common/head"); 
?>
</head>

<body>
<?php
    $navigation_date = array("active" => "content");
    $this->load->view("asterisk_common/navigation", $navigation_date); 
?>

<div class="container">
	<?php
            $this->load->view("asterisk_common/alert_container"); 
        ?>
	<div class="row">
		<?php echo $content["header"]; ?>
	</div>
	<div class="row" style="margin-top: 18px;">
		<div class="span12" id="search-holder">
			<?php echo $content["search"]; ?>
		</div>
	</div>
	<div class="row">
		<div class="span4">
                    <?php echo $content["controls"]; ?>
		</div>
		<div class="" id="pagination-holder">
                    <?php echo $content["pagination"]; ?>	
		</div>
	</div>
	<div class="row">
            <form id="table-form">
		<div class="span12" id="table-holder">
                        <?php echo $content["table"]; ?>
		</div>
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

<script src="<?php echo base_url("application/asterisk/asterisk_common/public/js/table.js")?>"></script>

</html>
