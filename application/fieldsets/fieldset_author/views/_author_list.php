<?php if($display == "full"){ ?>
<div id="author_list_modal" class="modal hide fade" style="width:640px;margin-left:-320px;">
    <div id="modal_work_area">
<?php } ?> 
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3>Authors:</h3>
        </div>
        <div class="modal-body" style="padding-top: 0px">

            <div class="author_list_modal_results">
                <div id="modal-message-holder" style="margin-top:4px;"></div>
                <a href="#" class="btn btn-success modal_action_create">New Author</a>
                <?php if ($authors->num_rows > 0) { ?>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width:25%">Display Name</th>
                                    <th style="width:25%">Position</th>            
                                    <th style="width:15%"></th>   
                                    <th style="width:35%">Actions</th>            
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($authors->result() as $result) { ?>
                                <tr>
                                    <td><?php echo $result->display_name; ?></td>
                                    <td><?php echo $result->position; ?></td>
                                    <td></td>
                                    <td>
                                        <a href="<?php echo site_url("/fieldset_author/fieldset_author_data_form/".$result->fieldset_author_data_id) ?>" class="btn btn-success modal_action_edit">Edit</a>
                                        <?php if($result->fieldset_author_mapping_id == null){ ?>
                                            <a href="<?php echo site_url("/fieldset_author/delete_fieldset_author_data/".$result->fieldset_author_data_id) ?>" class="btn btn-warning modal_action_remove">Delete</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                        </table>
                <?php } else { ?>
                        <p>No Authors Found.</p>
                <?php } ?>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn" data-dismiss="modal">Close</a>
        </div>
<?php if($display == "full"){ ?> 
    </div>
</div>
<?php } ?>
