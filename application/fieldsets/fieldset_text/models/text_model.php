<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class text_model extends base_fieldset_model {

    
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "text";
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
        $CI->db->where('fieldset_text_id', $fieldset_id);
        $CI->db->update('fieldset_text', $form);  
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_text');
        $CI->db->where('fieldset_text_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_text_id"] = get_object_property($fieldset, "fieldset_text_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            $form["text"] = get_object_property($fieldset, "text");
        }
        return $form;                    
    }

}