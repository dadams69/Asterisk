    <span id="content_name_span_edit" style="display: none;">
        <form id="content_name_form" action="<?php echo site_url("asterisk_content/edit_content_name/".$content->content_id); ?>" method="post">
            <input type="hidden" value="<?php echo $content->content_id; ?>" name="form[content_id]" />
            <input type="text" value="<?php echo $content->name; ?>" pre_value="<?php echo $content->name; ?>" name="form[name]" id="content_name_input"/>
            <a href="#" id="content_name_save">
                <i class="icon-ok icon-white"></i>
            </a>               
            <a href="#" id="content_name_cancel">
                <i class="icon-remove icon-white"></i>
            </a>                
        </form>            
    </span>    
    <span id="content_name_span_view">
        Content: <?php echo $content->name; ?> 
        <a href="#" id="content_name_edit">
            <i class="icon-pencil icon-white"></i>
        </a>
    </span>