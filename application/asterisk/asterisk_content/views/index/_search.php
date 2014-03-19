<div class="accordion" id="search-accordian">
        <div class="accordion-group">
                <?php
                        $search_form_title = "";
                        if (get_if_set($search,"name") != "") {
                                $search_form_title .= "Name: " . htmlentities($search["name"], ENT_COMPAT, "UTF-8") . " ";
                        } 
                        
                        if (get_if_set($search,"content_type") != "") {
                            if(get_if_set($search,"content_type") != "0" ){
                                if(get_if_set($content_types,get_if_set($search,"content_type"))!=""){
                                    $search_form_title .= "Type: ".get_if_set($content_types,get_if_set($search,"content_type"));
                                }
                            }else{
                                $search_form_title .= "Type: Generic ";
                            }
                        }                         
                        
                ?>
                <div class="accordion-heading"> <a class="accordion-toggle" data-toggle="collapse" data-parent="#search-accordian" href="#collapseOne"> Search: <?php echo ($search_form_title!="") ? $search_form_title : "No Filter"; ?> <i class="icon-search"></i> </a> </div>
                <div id="collapseOne" class="accordion-body collapse <?php echo ($search_form_title!="") ? "in" : ""; ?>">
                        <div class="accordion-inner">
                                <form class="form-horizontal ajax" action="<?php echo site_url("asterisk_content/index"); ?>">
                                        <fieldset>                                       
                                                <div class="control-group">
                                                    <label class="control-label" for="search_name">Name</label>
                                                    <div class="controls">
                                                        <input name="search[name]" type="text" class="input-xlarge" id="search_name" value="<?php echo get_if_set_htmlentities($search, "name") ; ?>">
                                                        <select name="search[content_type]" class="input-large" id="search_content_type">
                                                            <option value="" <?php echo (get_if_set($search,"content_type") == "")?"selected='selected'":""; ?>>All</option>
                                                            <option value="0" <?php echo (get_if_set($search,"content_type") == "0")?"selected='selected'":""; ?>>Generic</option>
                                                            <?php foreach ($content_types as $key => $value) { ?>
                                                                <option value="<?php echo $key; ?>" <?php echo (get_if_set($search,"content_type") == $key)?"selected='selected'":""; ?>><?php echo $value; ?></a></li>
                                                            <?php } ?>                                              
                                                        </select>
                                                    </div>
                                                </div>                                                                                        
                                                <div class="form-actions">
                                                        <button type="submit" class="btn btn-primary">Execute Filter</button>
                                                        <?php 
                                                        $q_no_search = $search["query_array"]; 
                                                        unset( $q_no_search["search"] );
                                                        ?>
                                                        <a href="<?php echo site_url("asterisk_content/index?clear=true&" .http_build_query($q_no_search)); ?>" class="btn">Clear Filter</a>
                                                </div>
                                        </fieldset>
                                </form>
                        </div>
                </div>
        </div>
</div>