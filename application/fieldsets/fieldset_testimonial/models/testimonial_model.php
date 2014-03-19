<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class testimonial_model extends base_fieldset_model {

    
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "testimonial";
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
        $CI->db->where('fieldset_testimonial_id', $fieldset_id);
        $CI->db->update('fieldset_testimonial', $form);  
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_testimonial');
        $CI->db->where('fieldset_testimonial_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_testimonial_id"] = get_object_property($fieldset, "fieldset_testimonial_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            $form["testimonial"] = get_object_property($fieldset, "testimonial");
            $form["source"] = get_object_property($fieldset, "source");
        }
        return $form;                    
    }

}