<?php if ($mode == "modal") { ?>
<div id="myModal" class="modal hide fade" style="width:825px;margin-left:-340px;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h3>Select file:</h3>
    </div>
    <div class="modal-body">

    <form class="form-inline pull-right">
        <label for="include_version">
            Show files for this version only
            <input type="checkbox" style="margin-top:-3px;" class="search-include-version" id="include_version" value="true" <?php if ($limit_to_version) { ?> checked="checked" <?php } ?>>
            &nbsp;&nbsp;&nbsp;
        </label>
        <input type="text" class="search-query input-xlarge" placeholder="Search (ie: keyword, size:___, type:___)">
    </form>        
<?php } ?>
        
<div class="file_select_modal_results">
<?php if ($files->num_rows > 0) { ?>
        <ul class="thumbnails">
            <?php foreach($files->result() as $result) { ?>
                <li class="span2">
                    <?php 
                    $file_data["mode"] = "modal_select";
                    $file_data["file_data"] = $result;
                    $this->load->view("_file", $file_data);
                    ?>
                </li>
            <?php } ?>
        </ul>
<?php } else { ?>
        <p>No files found.</p>
<?php } ?>
</div>
        
<?php if ($mode == "modal") { ?>
    </div>
    <div class="modal-footer">
        <a href="#" class="btn" data-dismiss="modal">Close</a>
    </div>
</div>
<?php } ?>
