<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class event_model extends base_fieldset_model {

    
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "event";
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
        $CI->load->database();        
        $CI->db->where('fieldset_event_id', $fieldset_id);
        $CI->db->update('fieldset_event', $form);  
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_event');
        $CI->db->where('fieldset_event_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_event_id"] = get_object_property($fieldset, "fieldset_event_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            $form["title"] = get_object_property($fieldset, "title");
            $form["description"] = get_object_property($fieldset, "description");
            $form["primary_image"] = get_object_property($fieldset, "primary_image");
            $form["location"] = get_object_property($fieldset, "location");
            $form["cost"] = get_object_property($fieldset, "cost");
            $form["primary_text"] = get_object_property($fieldset, "primary_text");
            $form["primary_link"] = get_object_property($fieldset, "primary_link");
        }
        return $form;                    
    }

}