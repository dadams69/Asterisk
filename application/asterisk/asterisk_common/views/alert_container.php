<div class="row" id="fixed-alert-container">
        <div id="message-holder" class="span12">
            <?php if (isset($message)) { ?>
            <div class="alert alert-<?php echo $message["type"]; ?> fade in">
                <a class='close' data-dismiss='alert' href='#'>×</a> <?php echo $message["text"]; ?>
            </div>
            <?php } ?>
        </div>    
</div>