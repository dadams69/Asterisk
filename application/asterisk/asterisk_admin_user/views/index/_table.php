<?php
$query_array_to_update = $table["query_array"];
generate_hidden_fields($query_array_to_update);
?>

<table class="table">
    <thead>
        <tr>
            <th style="width:5%"></th>
            <th style="width:5%"><?php order_by_header("asterisk_admin_user/index", "ID", "admin_user.admin_user_id", "ASC", $query_array_to_update); ?></th>
            <th style="width:35%"><?php order_by_header("asterisk_admin_user/index", "Name", "admin_user.last_name", "ASC", $query_array_to_update); ?></th>
            <th style="width:25%"><?php order_by_header("asterisk_admin_user/index", "Email", "admin_user.email", "ASC", $query_array_to_update); ?></th>            
            <th style="width:15%"></th>   
            <th style="width:15%"><?php order_by_header("asterisk_admin_user/index", "Last Login At", "admin_user.last_login_at", "ASC", $query_array_to_update); ?></th>            
        </tr>
    </thead>
    <tbody>
        <?php $results = $table["results"];
        if (count($results) == 0) {
            ?>
            <tr>
                <td colspan="9">No Results</td>
            </tr>
<?php } else {
    foreach ($results->result() as $result) {
        ?>
                <tr>
                    <td><label class="checkbox"><input type="checkbox" name="id[<?php echo $result->admin_user_id; ?>]" value="<?php echo $result->admin_user_id; ?>"></label></td>
                    <td><?php echo $result->admin_user_id; ?></td>
                    <td>
                        <a href="<?php echo site_url("asterisk_admin_user/form/" . $result->admin_user_id ); ?>"><?php echo htmlentities($result->last_name.", ".$result->first_name); ?></a>
                    </td>                                    
                    <td>
                        <a href="<?php echo site_url("asterisk_admin_user/form/" . $result->admin_user_id ); ?>"><?php echo htmlentities($result->email); ?></a>
                    </td>                                                        
                    <td>
                        <?php if ($result->status == "INACTIVE") { ?>
                            <span class="label label-info">Inactive</span>
                        <?php } elseif($result->status == "ACTIVE") { ?>
                            <span class="label label-success">Active</span>
                        <?php } ?>
                        <?php if ($result->super_admin == 1) { ?>
                            <span class="label label-important">Super Admin</span>
                        <?php } ?>
                    </td>
                    <td><?php 
                        if($result->last_login_at != null && $result->last_login_at != ""){
                            $lma = new DateTime($result->last_login_at);
                            echo $lma->format("m/d/Y H:i:s");
                        }
                    ?></td>
                    
                </tr>        
    <?php } ?>
<?php } ?>
    </tbody>
</table>