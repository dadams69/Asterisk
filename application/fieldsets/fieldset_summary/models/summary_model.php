<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class summary_model extends base_fieldset_model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "summary";
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

        if($form["image_size_1"] == ""){
            $form["image_size_1"] = null;
        }
        if($form["image_size_2"] == ""){
            $form["image_size_2"] = null;
        }        
        $CI->db->where('fieldset_summary_id', $fieldset_id);
        $CI->db->update('fieldset_summary', $form);  
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_summary');
        $CI->db->where('fieldset_summary_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_summary_id"] = get_object_property($fieldset, "fieldset_summary_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            $form["short_description"] = get_object_property($fieldset, "short_description");
            $form["quote"] = get_object_property($fieldset, "quote");
            $form["quote_attribution"] = get_object_property($fieldset, "quote_attribution");
            $form["image_size_1"] = get_object_property($fieldset, "image_size_1");
            $form["image_size_2"] = get_object_property($fieldset, "image_size_2");
        }
        return $form;                    
    }
    
}