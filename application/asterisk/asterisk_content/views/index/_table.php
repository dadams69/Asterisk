<?php
$query_array_to_update = $table["query_array"];
generate_hidden_fields($query_array_to_update);
?>

<table class="table">
    <thead>
        <tr>
            <th style="width:5%"></th>
            <th style="width:5%"><?php order_by_header("asterisk_content/index", "ID", "content.content_id", "ASC", $query_array_to_update); ?></th>
            <th style="width:45%"><?php order_by_header("asterisk_content/index", "Name", "content.name", "ASC", $query_array_to_update); ?></th>
            <th style="width:15%"><?php order_by_header("asterisk_content/index", "Type", "content_type.name", "ASC", $query_array_to_update); ?></th>            
            <th style="width:15%"><?php order_by_header("asterisk_content/index", "Modified", "content.last_modified_at", "ASC", $query_array_to_update); ?></th>            
            <th style="width:5%">Version</th>            
            <th style="width:5%">Production</th>   
            <th style="width:5%">Staging</th>   
        </tr>
    </thead>
    <tbody>
        <?php $results = $table["results"];
        if (count($results) == 0) {
            ?>
            <tr>
                <td colspan="8">No Results</td>
            </tr>
<?php } else {
    foreach ($results->result() as $result) {
        ?>
                <tr>
                    <td><label class="checkbox"><input type="checkbox" name="id[<?php echo $result->content_id; ?>]" value="<?php echo $result->content_id; ?>"></label></td>
                    <td><?php echo $result->content_id; ?></td>
                    <td>
                        <a href="<?php echo site_url("asterisk_content/form/" . $result->content_id . "/" . $result->max_version_version_id); ?>"><?php echo htmlentities($result->name); ?></a>
                    </td>                                    
                    <td><?php echo ($result->content_type_name)?$result->content_type_name:"Generic"; ?></td>
                    <td><?php 
                        $lma = new DateTime($result->last_modified_at);
                        echo $lma->format("m/d/Y H:i:s");
                    ?></td>
                    <td><?php if ($result->max_version_major_version) { ?>
                        <a href="<?php echo site_url("asterisk_content/form/" . $result->content_id . "/" . $result->max_version_version_id ); ?>"><?php echo $result->max_version_major_version; ?></a>
                    <?php } else {
                        echo "-";
                    } ?></td>
                    <td><?php if ($result->production_version_major_version) { ?>
                        <a href="<?php echo site_url("asterisk_content/form/" . $result->content_id . "/" . $result->production_version_version_id ); ?>"><?php echo $result->production_version_major_version; ?></a>
                    <?php } else {
                        echo "-";
                    } ?></td>
                    <td><?php if ($result->staging_version_major_version) { ?>
                        <a href="<?php echo site_url("asterisk_content/form/" . $result->content_id . "/" . $result->staging_version_version_id ); ?>"><?php echo $result->staging_version_major_version; ?></a>
                    <?php } else {
                        echo "-";
                    } ?></td>
                </tr>        
    <?php } ?>
<?php } ?>
    </tbody>
</table>