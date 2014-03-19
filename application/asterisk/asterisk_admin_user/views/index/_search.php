<div class="accordion" id="search-accordian">
        <div class="accordion-group">
                <?php
                        $search_form_title = "";
                        if (get_if_set($search,"email") != "") {
                                $search_form_title .= "Email: " . htmlentities($search["email"], ENT_COMPAT, "UTF-8") . " ";
                        } 
                        
                ?>
                <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#search-accordian" href="#collapseOne"> Search: <?php echo ($search_form_title!="") ? $search_form_title : "No Filter"; ?> <i class="icon-search"></i> </a> </div>
                <div id="collapseOne" class="accordion-body collapse <?php echo ($search_form_title!="") ? "in" : ""; ?>">
                        <div class="accordion-inner">
                                <form class="form-horizontal ajax" action="<?php echo site_url("asterisk_admin_user/index"); ?>">
                                        <fieldset>                                       
                                                <div class="control-group">
                                                    <label class="control-label" for="search_name">Email</label>
                                                    <div class="controls">
                                                        <input name="search[email]" type="text" class="input-xlarge" id="search_email" value="<?php echo get_if_set_htmlentities($search, "email") ; ?>">
                                                    </div>
                                                </div>                                                                                        
                                                <div class="form-actions">
                                                        <button type="submit" class="btn btn-primary">Execute Filter</button>
                                                        <?php 
                                                        $q_no_search = $search["query_array"]; 
                                                        unset( $q_no_search["search"] );
                                                        ?>
                                                        <a href="<?php echo site_url("asterisk_admin_user/index?clear=true&" .http_build_query($q_no_search)); ?>" class="btn">Clear Filter</a>
                                                </div>
                                        </fieldset>
                                </form>
                        </div>
                </div>
        </div>
</div>