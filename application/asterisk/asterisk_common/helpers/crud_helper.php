<?php

function override_parameter($query_array, $key, $value) {
    $query_array[$key] = $value;
    return $query_array;
}

function generate_hidden_fields($query, $stack = array()) {
    if(is_array($query)){ 
        foreach( $query as $key => $key_value ){
            if (is_array($key_value)) {
                $prev_stack = $stack;
                $stack[] = $key;
                generate_hidden_fields($key_value, $stack);
                $stack = $prev_stack;
            } else {
                $local_stack = $stack;
                $local_stack[] = $key;
                $s = "";
                foreach ($local_stack as $k) {
                    if ($s == "") {
                        $s = $k;
                    } else {
                        $s .= "[" . $k . "]";
                    }
                }
                echo '<input type="hidden" name="' . $s . '" value="' . urlencode( $key_value ) . '" />';
            }
        }
    }
}
function order_by_header($base_url, $title, $order_by_sort, $default_order_by_order, $query_array_to_update) {
    $current_order_by_sort = isset($query_array_to_update["order_by_sort"]) ? $query_array_to_update["order_by_sort"] : "";
    if ($current_order_by_sort == $order_by_sort) { 
        $new_direction = ($query_array_to_update["order_by_order"] == "ASC") ? "DESC" : "ASC";
        $q = override_parameter($query_array_to_update, "order_by_order", $new_direction);
        ?>
               <a href="<?php echo site_url($base_url . "?" .http_build_query($q)); ?>" class="ajax"><?php echo $title ?> 
                <?php if ($new_direction == "DESC") {
                    ?><i class="icon-chevron-down"></i><?php
                } else {
                    ?><i class="icon-chevron-up"></i><?php
                } ?></a>
    <?php
    } else {
        $q = override_parameter($query_array_to_update, "order_by_sort", $order_by_sort);
        $q = override_parameter($q, "order_by_order", $default_order_by_order);
        ?>
               <a href="<?php echo site_url($base_url . "?" .http_build_query($q)); ?>" class="ajax"><?php echo $title ?> </a>
        <?php
    }
}

function decode_array($query_array){
    $decoded_array = array();
    foreach($query_array as $query_key => $query_value){
        if(is_array($query_value)){
            $decoded_array[$query_key] = decode_array($query_value);
        }else{
            $decoded_array[$query_key] = urldecode($query_value);
        }
    }
    return $decoded_array;
}

function get_if_set($array, $keys, $default_value="") {
    if ($array) {
        $keys = explode(">", $keys );
        foreach ($keys as $key) {
            if (is_array($array) && isset($array[$key]) && $array[$key] != "") {
                $array = $array[$key];
            } else {
                return $default_value;
            }
        }
        return $array;
    }
    return $default_value;
}

function getdate_if_set($formats, $array, $keys, $default_value=null) {
    if($array){
        $keys = explode(">", $keys );
        foreach ($keys as $key) {
            if (is_array($array) && isset($array[$key]) && $array[$key] != "") {
                $array = $array[$key];
            } else {
                return $default_value;
            }
        }
        $formats = explode(">", $formats );
        foreach($formats as $format){
            if ($array && DateTime::createFromFormat($format, $array)) {
                return $array = DateTime::createFromFormat($format, $array);
            }
        }
    }
    return $default_value;
}

function get_if_set_htmlentities($array, $keys, $default_value="") {
    if ($array) {
        $keys = explode(">", $keys );
        foreach ($keys as $key) {
            if (is_array($array) && isset($array[$key]) && $array[$key] != "") {
                $array = $array[$key];
            } else {
                return htmlentities($default_value,ENT_COMPAT,"UTF-8");
            }
        }
        return htmlentities($array,ENT_COMPAT,"UTF-8");
    }
    return htmlentities($default_value,ENT_COMPAT,"UTF-8");
}

function getdate_with_format($format, $element, $default_value = ""){
    if($element){
        return $element->format($format);
    }else{
        return $default_value;
    }
}

function convert_string_date_format($old_format, $new_format, $string, $default_value = ""){
    if(DateTime::createFromFormat($old_format, $string)){
       return DateTime::createFromFormat($old_format, $string)->format($new_format);
    }else{
        return $default_value;
    }
}

function get_object_property($object, $getter, $default_value = ""){
    if ($object) {
        if (property_exists($object,$getter)) {
            return $object->$getter;
        } else {
            return $default_value;
        }
    } else {
        return $default_value;
    }
}

function get_boolean_if_in_array($array, $key, $value){
    $find_array = get_if_set($array, $key);
    if(is_array($find_array)){
        return in_array($value, $find_array);                
    }else{
        if($find_array == $value)
            return true;
        else
            return false;
    }
}

function form_field_input( $settings = array() ) {
                $label = get_if_set($settings, "label");
                $name = get_if_set($settings, "name");
                $type = get_if_set($settings, "type");
                $input_class = get_if_set($settings, "input_class");
                $id = get_if_set($settings, "id");
                $value = get_if_set($settings, "value");
                $placeholder = get_if_set($settings, "placeholder");
                $control_group_class = get_if_set($settings, "control_group_class");
                $help_inline = get_if_set($settings, "help_inline");
                $help_block = get_if_set($settings, "help_block");
                $checked = get_if_set($settings, "checked", null);
                $extra_attr = get_if_set($settings, "extra_attr", "");
                $value = htmlentities($value, ENT_COMPAT, "UTF-8");
?>                <div class="control-group <?php echo $control_group_class; ?>">
                    <label class="control-label" for="<?php echo $id; ?>"><?php echo $label; ?></label>
                    <div class="controls">
                        <input name="<?php echo $name; ?>" type="<?php echo $type; ?>" class="<?php echo $input_class; ?>" id="<?php echo $id; ?>" value="<?php echo $value; ?>" placeholder="<?php echo $placeholder; ?>" <?php if ($checked) echo 'checked="checked"'?> <?php echo $extra_attr; ?>>
                        <?php if ($help_inline !="") { ?>
                            <span class="help-inline"><?php echo $help_inline; ?></span>
                        <?php } ?>
                        <?php if ($help_block !="") { ?>
                            <span class="help-block"><?php echo $help_block; ?></span>
                        <?php } ?>
                    </div>
                </div>
<?php
}

function form_field_select( $settings = array()) {
                $label = get_if_set($settings, "label");
                $name = get_if_set($settings, "name");
                $select_class = get_if_set($settings, "select_class");
                $id = get_if_set($settings, "id");
                $value = get_if_set($settings, "value");
                $options = get_if_set($settings, "options");
                $control_group_class = get_if_set($settings, "control_group_class");
                $help_inline = get_if_set($settings, "help_inline");
                $help_block = get_if_set($settings, "help_block");
                $file_dir = get_if_set($settings, "file_dir");
                $relative_url = get_if_set($settings, "relative_url");
                $source = get_if_set($settings, "source");
                $multiple = get_if_set($settings, "multiple", false);
                $multiple_size = get_if_set($settings, "multiple_size",null);
?>                <div class="control-group <?php echo $control_group_class; ?>">
                    <label class="control-label" for="<?php echo $id; ?>"><?php echo $label; ?></label>
                    <div class="controls">
                        <select id="<?php echo $id; ?>" class="<?php echo $select_class; ?>"  name="<?php echo $name; ?>" file_dir="<?php echo $file_dir; ?>" <?php if($relative_url !=""){ echo 'relative_url="'.$relative_url.'"'; } ?> source="<?php echo $source; ?>"<?php echo($multiple)?" multiple='multiple'":""; ?><?php echo($multiple_size)?" size='".$multiple_size."'":""; ?>>
                            <?php foreach($options as $key => $val) { 
                                if(is_array($value)){
                                    $select = ( in_array($key,$value)) ? " selected='selected' " : ""; 
                                }else{
                                    $select = ($key == $value) ? " selected='selected' " : ""; 
                                }
                            ?>
                                <option <?php echo $select; ?> value="<?php echo $key; ?>"><?php echo $val; ?></option>
                            <?php } ?>
                        </select>
                    <?php if ($help_inline !="") { ?>
                            <span class="help-inline"><?php echo $help_inline; ?></span>
                    <?php } ?>
                    <?php if ($help_block !="") { ?>
                        <span class="help-block"><?php echo $help_block; ?></span>
                    <?php } ?>                        
                    </div>
                </div>
<?php
}

function form_field_checkbox_group( $settings = array()) {
                $label = get_if_set($settings, "label");
                $name = get_if_set($settings, "name");
                $input_class = get_if_set($settings, "input_class");
                $id = get_if_set($settings, "id");
                $values = get_if_set($settings, "values");
                $options = get_if_set($settings, "options");
                $control_group_class = get_if_set($settings, "control_group_class");
                $help_inline = get_if_set($settings, "help_inline");
                $help_block = get_if_set($settings, "help_block");
                if (!is_array($values)) {
                    $values = array($values => "true");
                }

?>                <div class="control-group <?php echo $control_group_class; ?>">
                    <label class="control-label" for="<?php echo $id; ?>"><?php echo $label; ?></label>
                    <div class="controls">
                        <?php foreach($options as $key => $val) { 
                            $checked = (in_array($key, $values)) ? " checked='checked' " : ""; 
                            ?>
                            <label class="checkbox"><input type="checkbox" name="<?php echo $name; ?>[]" <?php echo $checked; ?> class="<?php echo $input_class; ?>" value="<?php echo $key; ?>"><?php echo $val; ?></label>
                        <?php } ?>
                        <?php if ($help_inline !="") { ?>
                                <span class="help-inline"><?php echo $help_inline; ?></span>
                        <?php } ?>
                        <?php if ($help_block !="") { ?>
                            <span class="help-block"><?php echo $help_block; ?></span>
                        <?php } ?>                            
                    </div>
                </div>
<?php
}

function form_field_radio_group( $settings = array()) {
                $label = get_if_set($settings, "label");
                $name = get_if_set($settings, "name");
                $input_class = get_if_set($settings, "input_class");
                $id = get_if_set($settings, "id");
                $values = get_if_set($settings, "values");
                $radios = get_if_set($settings, "radios");
                $control_group_class = get_if_set($settings, "control_group_class");
                $help_inline = get_if_set($settings, "help_inline");
                $help_block = get_if_set($settings, "help_block");

?>                <div class="control-group <?php echo $control_group_class; ?>">
                    <label class="control-label" for="<?php echo $id; ?>"><?php echo $label; ?></label>
                    <div class="controls">
                        <?php foreach($radios as $key => $val) { 
                            if(is_array($values))
                                $checked = (in_array($key, $values)) ? " checked='checked' " : ""; 
                            else
                                $checked = ($key == $values) ? " checked='checked' " : "";     
                            ?>                            
                            <label class="radio"><input type="radio" name="<?php echo $name; ?>" <?php echo $checked; ?> class="<?php echo $input_class; ?>" value="<?php echo $key; ?>"><?php echo $val; ?></label>
                        <?php } ?>
                    </div>
                    <?php if ($help_inline !="") { ?>
                            <span class="help-inline"><?php echo $help_inline; ?></span>
                    <?php } ?>
                    <?php if ($help_block !="") { ?>
                        <span class="help-block"><?php echo $help_block; ?></span>
                    <?php } ?>
                </div>
<?php
}

function form_field_textarea( $settings = array() ) {

                $label = get_if_set($settings, "label");
                $name = get_if_set($settings, "name");
                $textarea_class = get_if_set($settings, "input_class");
                $id = get_if_set($settings, "id");
                $value = get_if_set($settings, "value");
                $placeholder = get_if_set($settings, "placeholder");
                $control_group_class = get_if_set($settings, "control_group_class");
                $style = get_if_set($settings, "style");
                $rows = get_if_set($settings, "rows");
                $help_inline = get_if_set($settings, "help_inline");
                $help_block = get_if_set($settings, "help_block");
                
                $value = htmlentities($value, ENT_COMPAT, "UTF-8");
?>                <div class="control-group <?php echo $control_group_class; ?>">
                    <label class="control-label" for="<?php echo $id; ?>"><?php echo $label; ?></label>
                    <div class="controls">
                        <textarea name="<?php echo $name; ?>" class="<?php echo $textarea_class; ?>" style="<?php echo $style; ?>" rows="<?php echo $rows; ?>" id="<?php echo $id; ?>" placeholder="<?php echo $placeholder; ?>"><?php echo $value; ?></textarea>
                        <?php if ($help_inline !="") { ?>
                            <span class="help-inline"><?php echo $help_inline; ?></span>
                        <?php } ?>
                        <?php if ($help_block !="") { ?>
                            <span class="help-block"><?php echo $help_block; ?></span>
                        <?php } ?>
                    </div>
                </div>
<?php
}


function get_json_element_list($form, $validation, $namespace) {
    $a = array();
    if(isset($form[$namespace])){
        foreach($form[$namespace] as $k=>$elm) {
            $elm["validation"] = get_if_set($validation, "fields>$namespace>$k");
            $a[]=$elm;
        }
    }
    
    return json_encode($a);
    
}

function form_template_input ($settings = array()) {
    $template_field = get_if_set($settings, "template_field", null);
    $type = get_if_set($settings, "type", "text");
    $name = get_if_set($settings, "name", null);
    $class = get_if_set($settings, "input_class", "input-small");
    $label = get_if_set($settings, "label");
    $id = get_if_set($settings, "id");
    $placeholder = get_if_set($settings, "placeholder");
    $disabled = get_if_set($settings, "disabled");
    $default_value = get_if_set($settings, "default_value",null);
    $extra_attr = get_if_set($settings, "extra_attr", "");
    if (!$template_field || !$name) throw new Exception("Function form_template_input - Input template_field and name are required");
    
?>
    <div class="control-group {{if validation.<?php echo $template_field ?>}}${validation.<?php echo $template_field ?>.type}{{/if}}">
        <label class="control-label" for="<?php echo $id ?>"><?php echo $label ?></label>
        <div class="controls">
            <input name="<?php echo $name ?>" type="<?php echo $type ?>" value="<?php echo ($default_value)?$default_value:'${'.$template_field.'}'; ?>" <?php if ($class) echo 'class="'.$class.'"'?> <?php if ($id) echo 'id="'.$id.'"' ?> <?php if ($placeholder) echo 'placeholder="'.$placeholder.'"' ?> <?php if ($disabled) echo 'disabled="disabled"' ?> <?php if($type=="checkbox"){echo '{{if '.$template_field.' }} checked="checked" {{/if}}';} ?> <?php echo $extra_attr; ?>/>
            {{if validation.<?php echo $template_field ?>}}
                <span class="help-inline">${validation.<?php echo $template_field ?>.message}</span>
            {{/if}}
        </div>
    </div>               
<?php 
    
}

function form_template_textarea ($settings = array()) {
    $template_field = get_if_set($settings, "template_field", null);
    $name = get_if_set($settings, "name", null);
    $class = get_if_set($settings, "input_class");
    $label = get_if_set($settings, "label");
    $id = get_if_set($settings, "id");
    $placeholder = get_if_set($settings, "placeholder");
    $rows = get_if_set($settings, "rows");
    
    if (!$template_field || !$name) throw new Exception("Function form_template_textarea - Input template_field and name are required");

?>
        <div class="control-group {{if validation.<?php echo $template_field ?>}}${validation.<?php echo $template_field ?>.type}{{/if}}">
            <label class="control-label" for="<?php echo $id; ?>"><?php echo $label; ?></label>
            <div class="controls">
                <textarea name="<?php echo $name ?>" class="<?php echo $class; ?>" rows="<?php echo $rows; ?>" id="<?php echo $id; ?>" placeholder="<?php echo $placeholder; ?>">${<?php echo $template_field ?>}</textarea>
                {{if validation.<?php echo $template_field ?>}}
                    <span class="help-inline">${validation.<?php echo $template_field ?>.message}</span>
                {{/if}}
            </div>
        </div>
<?php 
    
}

function form_template_dropdown ($settings = array()) {
    $template_field = get_if_set($settings, "template_field", null);
    $name = get_if_set($settings, "name", null);
    $options = get_if_set($settings, "options", "text", null);
    $class = get_if_set($settings, "input_class", "input-small");
    $label = get_if_set($settings, "label");
    $id = get_if_set($settings, "id");
    $placeholder = get_if_set($settings, "placeholder");
    $disabled = get_if_set($settings, "disabled", false);
    $extra_option = get_if_set($settings, "extra_option", false);
    
    if (!$template_field || !$name || !$options) throw new Exception("Function form_template_dropdown - Input template_field, name and options are required");

?>
    <div class="control-group {{if validation.<?php echo $template_field ?>}}${validation.<?php echo $template_field ?>.type}{{/if}}">
        <label class="control-label" for="<?php echo $id ?>"><?php echo $label ?></label>
        <div class="controls">
            <select name="<?php echo $name ?>" <?php if ($id) echo 'id="'.$id.'"' ?> <?php if ($disabled) echo 'disabled="disabled"' ?> class="<?php echo $class; ?>">
                <?php if ($extra_option): ?><option value=""><?php echo $extra_option ?></option><?php endif; ?>
                <?php foreach($options as $k=>$v): ?>
                    <option value="<?php echo $k ?>" {{if <?php echo $template_field ?>=='<?php echo $k ?>' }}selected="selected"{{/if}} ><?php echo $v ?></option>
                <?php endforeach; ?>
            </select>
            {{if validation.<?php echo $template_field ?>}}
                <span class="help-inline">${validation.<?php echo $template_field ?>.message}</span>
            {{/if}}
        </div>
    </div>               
<?php 
    
}               

function form_template_link ($settings = array()) {
    $href = get_if_set($settings, "href", "#");
    $label = get_if_set($settings, "label", null);
    $class = get_if_set($settings, "link_class");
    $target = get_if_set($settings, "target", "_self");
    if (!$label) throw new Exception("Function form_template_link - Link label is required");

?>
    <div class="control-group">
        <a style="${style}" href="<?php echo $href ?>" class="<?php echo $class ?>" target="<?php echo $target; ?>"><?php echo $label ?></a>
    </div>               
               
<?php 
}

function form_field_select_doctrine ( $settings = array()) {
    $object_class = get_if_set($settings, "object_class");
    $object_id = get_if_set($settings, "object_id");
    $object_label = get_if_set($settings, "object_label");
    $object_order_by = get_if_set($settings, "object_order_by", null);
    $option_extra = get_if_set($settings, "option_extra", null);
    
    $getter_id = "get".underscore_to_camelcase($object_id);
    $getter_label = "get".underscore_to_camelcase($object_label);
    
    $em = doctrine_get_em();
    $elements = $em->getRepository($object_class)->findBy(array(), $object_order_by);
    
    $options = $option_extra ? array("" => $option_extra) : array();
    foreach ($elements as $element) {
        $options[$element->$getter_id()] =  $element->$getter_label();
    }
    
    $settings["options"] = $options;
    
    form_field_select($settings);
}