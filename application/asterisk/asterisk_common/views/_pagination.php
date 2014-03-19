<?php
$query_array_to_update = $pagination["query_array"];
?>

<div class="span3">
    <div class="btn-toolbar" style="margin-top: 18px;">
        <div class="btn-group">
                <button class="btn dropdown-toggle" data-toggle="dropdown"><span><?php echo $pagination["total_rows"]; ?> Rows, <?php echo $pagination["rows_per_page"]; ?> Per Page <span class="caret"></span></button>
                <ul class="dropdown-menu">
                        <?php $q_per = override_parameter($query_array_to_update, "rows_per_page", 10); ?>
                        <li><a href="<?php echo site_url($base_url . "?" .http_build_query($q_per)); ?>" class="ajax">10 per page</a></li>
                        <?php $q_per = override_parameter($query_array_to_update, "rows_per_page", 25); ?>
                        <li><a href="<?php echo site_url($base_url . "?" .http_build_query($q_per)); ?>" class="ajax">25 per page</a></li>
                        <?php $q_per = override_parameter($query_array_to_update, "rows_per_page", 100); ?>
                        <li><a href="<?php echo site_url($base_url . "?" .http_build_query($q_per)); ?>" class="ajax">100 per page</a></li>
                </ul>
        </div>
    </div>
    <!-- /btn-group --> 
</div>
<div class="span5">

    <div class="pagination pagination-right">
            <ul>
                <?php
                if ($pagination["page"] > 1) { 
                    $q_first = override_parameter($query_array_to_update, "page", 1);
                    $q_prev = override_parameter($query_array_to_update, "page", $pagination["page"] - 1);
                    ?>
                    <li><a href="<?php echo site_url($base_url . "?" .http_build_query($q_first)); ?>" class="ajax">&lt;&lt;</a></li>    
                    <li><a href="<?php echo site_url($base_url . "?" .http_build_query($q_prev)); ?>" class="ajax">&lt;</a></li>
                <?php } else { ?>
                    <li class="disabled"><a href="#">&lt;&lt;</a></li>
                    <li class="disabled"><a href="#">&lt;</a></li>
                <?php } ?>


                <?php
                for ($i=1; $i<=$pagination["total_pages"]; $i++){ 
                    $q = override_parameter($query_array_to_update, "page", $i);
                    $active = ($i == $pagination["page"]) ? "active" : "";
                    $range = 1;
                    if ($pagination["total_pages"] < 8 || $i <= $range || $i > $pagination["total_pages"] - $range  || abs($i - $pagination["page"]) <= $range) {
                        $mode = "normal";
                    ?>
                        <li class="<?php echo $active; ?>"><a href="<?php echo site_url($base_url . "?" .http_build_query($q)); ?>" class="ajax"><?php echo $i; ?></a></li>
                    <?php } else {
                        if ($mode == "normal") { ?>
                            <li class="disabled"><a href="#">...</a></li>
                            <?php 
                            $mode = "disabled";
                        } ?>
                    <?php } ?>
                <?php } ?>


                <?php 
                if ($pagination["page"] < $pagination["total_pages"]) { 
                    $q_next = override_parameter($query_array_to_update, "page", $pagination["page"] + 1);
                    $q_last = override_parameter($query_array_to_update, "page", $pagination["total_pages"]);
                    ?>
                    <li><a href="<?php echo site_url($base_url . "?" . http_build_query($q_next)); ?>" class="ajax">&gt;</a></li>
                    <li><a href="<?php echo site_url($base_url . "?" . http_build_query($q_last)); ?>" class="ajax">&gt;&gt;</a></li>
                <?php } else { ?>
                    <li class="disabled"><a href="#">&gt;</a></li>
                    <li class="disabled"><a href="#">&gt;&gt;</a></li>
                <?php } ?>

            </ul>    
    </div>
</div>