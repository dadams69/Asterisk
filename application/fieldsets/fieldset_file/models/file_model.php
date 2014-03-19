<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class file_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "file";
        $this->auxillary_tables = array("fieldset_file_mapping");
    }

    function validate($form) {
        $validation = array();
        
        if (isset($validation["fields"])) {
            $validation["message"]["type"] = "error";
            $validation["message"]["text"] = "Please check your form for errors.";
            $validation["error"] = true;
        } else {
            $validation["error"] = false;
        }

        return $validation;           
    }

    function validate_data_information($form){
        $validation = array();
        if (get_if_set($form, "filename") == "") {
            $validation["fields"]["filename"]["message"]["type"] = "error";
            $validation["fields"]["filename"]["message"]["text"] = "Title is required.";
        }        
        if (get_if_set($form, "title") == "") {
            $validation["fields"]["title"]["message"]["type"] = "error";
            $validation["fields"]["title"]["message"]["text"] = "Title is required.";
        }
        if (isset($validation["fields"])) {
            $validation["message"]["type"] = "error";
            $validation["message"]["text"] = "Please check your form for errors.";
            $validation["error"] = true;
        } else {
            $validation["error"] = false;
        }

        return $validation;        
    }    
    
    function array_to_db($fieldset_file_data_id,$form){
        if($form["previous_filename"] != $form["filename"]){
        $form["filename"] = str_replace(" ", "_", $form["filename"]);
          rename(dirname($_SERVER['SCRIPT_FILENAME']).'/public/files/'.$form["fieldset_file_data_id"].'/'.$form["previous_filename"].".".$form["extension"],
                  dirname($_SERVER['SCRIPT_FILENAME']).'/public/files/'.$form["fieldset_file_data_id"].'/'.$form["filename"].".".$form["extension"]);  
        }
        unset($form["previous_filename"]);
        $CI = & get_instance();
        $CI->load->database();        
        $CI->db->where('fieldset_file_data_id', $fieldset_file_data_id);
        $CI->db->update('fieldset_file_data', $form);  
    }    
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_file_data.fieldset_file_data_id, fieldset_file_data.filename, fieldset_file_data.extension, fieldset_file_data.title, fieldset_file_data.description, fieldset_file_data.credit, fieldset_file_data.keywords, fieldset_file_data.size, fieldset_file_data.image_size_key, fieldset_file_data.image_height, fieldset_file_data.image_width ');
        $CI->db->from('fieldset_file_mapping as fieldset_file_mapping');
        $CI->db->join('fieldset_file_data as fieldset_file_data', "fieldset_file_data.fieldset_file_data_id = fieldset_file_mapping.fieldset_file_data_id","join");
        $CI->db->where('fieldset_file_mapping.fieldset_file_id', $fieldset_id);
        
        $form["fieldset_key"] = $this->fieldset_key;
        $form["fieldset_file_id"] = $fieldset_id;
        $form["files"] = array();
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                $arr_elm = array();
                $arr_elm["fieldset_file_data_id"] = get_object_property($result, "fieldset_file_data_id");
                $arr_elm["filename"] = get_object_property($result, "filename");
                $arr_elm["extension"] = get_object_property($result, "extension");
                $arr_elm["title"] = get_object_property($result, "title");
                $arr_elm["description"] = get_object_property($result, "description");
                $arr_elm["credit"] = get_object_property($result, "credit");
                $arr_elm["keywords"] = get_object_property($result, "keywords");
                $arr_elm["size"] = get_object_property($result, "size");
                $arr_elm["image_size_key"] = get_object_property($result, "image_size_key");
                $arr_elm["image_height"] = get_object_property($result, "image_height");
                $arr_elm["image_width"] = get_object_property($result, "image_width");
                $form["files"][] = $arr_elm;
            }
        }
        
        return $form;                    
    }

    function file_data_to_array($fieldset_file_data_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_file_data as fieldset_file_data');
        $CI->db->where('fieldset_file_data.fieldset_file_data_id', $fieldset_file_data_id);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_file_data_id"] = get_object_property($fieldset, "fieldset_file_data_id");
            $form["filename"] = get_object_property($fieldset, "filename");
            $form["previous_filename"] = get_object_property($fieldset, "filename");
            $form["extension"] = get_object_property($fieldset, "extension");
            $form["title"] = get_object_property($fieldset, "title");
            $form["description"] = get_object_property($fieldset, "description");
            $form["credit"] = get_object_property($fieldset, "credit");
            $form["keywords"] = get_object_property($fieldset, "keywords");            
            $form["size"] = get_object_property($fieldset, "size");            
            $form["image_size_key"] = get_object_property($fieldset, "image_size_key");            
            $form["image_height"] = get_object_property($fieldset, "image_height");            
            $form["image_width"] = get_object_property($fieldset, "image_width");            
        }
        return $form;                            
    }
    
    function delete($fieldset_id) {
        parent::delete($fieldset_id);
        $this->delete_non_used_files();  
        return true;
    }

    function delete_non_used_files(){
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_file_data.fieldset_file_data_id,fieldset_file_data.filename,fieldset_file_data.extension');        
        $CI->db->from('fieldset_file_data');
        $CI->db->join("fieldset_file_mapping","fieldset_file_mapping.fieldset_file_data_id = fieldset_file_data.fieldset_file_data_id","LEFT");        
        $CI->db->where('fieldset_file_mapping.fieldset_file_data_id', null);
        $CI->db->where('fieldset_file_mapping.fieldset_file_id', null);        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_file_data){
                if(file_exists(dirname($_SERVER['SCRIPT_FILENAME']).'/public/files/'.$fieldset_file_data->fieldset_file_data_id.'/'.$fieldset_file_data->filename.".".$fieldset_file_data->extension)){
                    unlink(dirname($_SERVER['SCRIPT_FILENAME']).'/public/files/'.$fieldset_file_data->fieldset_file_data_id.'/'.$fieldset_file_data->filename.".".$fieldset_file_data->extension);     
                    rmdir(dirname($_SERVER['SCRIPT_FILENAME']).'/public/files/'.$fieldset_file_data->fieldset_file_data_id.'/');     
                }
                $CI->db->where('fieldset_file_data.fieldset_file_data_id', $fieldset_file_data->fieldset_file_data_id);        
                $CI->db->delete("fieldset_file_data");                    
            }
        }                    
    }
     
    function get_file_size_string($file_size){
        if($file_size && is_numeric($file_size)){
            if ($file_size >= 1000000000)
                return round(($file_size / 1000000000),2).' GB';
            if ($file_size >= 1000000) 
                return round(($file_size / 1000000),2).' MB';
            else
                return round(($file_size / 1000),2).' KB';                    
        }else{
            return "";
        }
            
    }
    
    function get_size_key_array($fieldset_id){
        $group_keys = array();
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_config.parameters');
        $CI->db->from('fieldset_file');
        $this->db->join('fieldset_config', 'fieldset_file.fieldset_config_id = fieldset_config.fieldset_config_id');
        $CI->db->where('fieldset_file.fieldset_file_id', $fieldset_id);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            parse_str($query->row('parameters'), $parameters);
            if(isset($parameters["size"])){
                $group_keys = $parameters["size"];
            }
        }
        return $group_keys;        
    }
    
    function get_size_key_name($fieldset_id, $size_key){
        if($size_key){
            $CI =& get_instance();
            $CI->load->database();
            $CI->db->select('fieldset_config.parameters');
            $CI->db->from('fieldset_file');
            $this->db->join('fieldset_config', 'fieldset_file.fieldset_config_id = fieldset_config.fieldset_config_id');
            $CI->db->where('fieldset_file.fieldset_file_id', $fieldset_id);
            $query = $CI->db->get();
            if ($query->num_rows() > 0) {
                parse_str($query->row('parameters'), $parameters);
                if(isset($parameters["size"]) && isset($parameters["size"]["$size_key"]) && isset($parameters["size"]["$size_key"]["name"])){
                    return $parameters["size"]["$size_key"]["name"];
                }
            }
        }
        return "";                
    }
}