<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class link_model extends base_fieldset_model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "link";
    }

    function validate($form){
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
    
    function array_to_db($fieldset_id,$form){
        $CI = & get_instance();
        //TODO: work on this after image selector implementation
        if($form["link_image"] == ""){
            $form["link_image"] = null;
        }
        $form["date"] = convert_string_date_format("m/d/Y H:i:s", "Y-m-d H:i:s", $form["date"], null);
        $CI->load->database();        
        $CI->db->where('fieldset_link_id', $fieldset_id);
        $CI->db->update('fieldset_link', $form);  
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_link');
        $CI->db->where('fieldset_link_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_link_id"] = get_object_property($fieldset, "fieldset_link_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            
            $form["title"] = get_object_property($fieldset, "title");
            $form["url"] = get_object_property($fieldset, "url");
            $form["link_image"] = get_object_property($fieldset, "link_image");
            $form["description"] = get_object_property($fieldset, "description");
            $form["source"] = get_object_property($fieldset, "source");            
            $date = DateTime::createFromFormat("Y-m-d H:i:s", get_object_property($fieldset, "date",null));
            $form["date"] = ($date)?$date->format("m/d/Y H:i:s"):null;
        }
        return $form;                    
    }
     
}