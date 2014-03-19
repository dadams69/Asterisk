<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class bio_model extends base_fieldset_model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "bio";
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
        if($form["thumbnail"] == ""){
            $form["thumbnail"] = null;
        }
        if($form["full_image"] == ""){
            $form["full_image"] = null;
        }
        $CI->load->database();        
        $CI->db->where('fieldset_bio_id', $fieldset_id);
        $CI->db->update('fieldset_bio', $form);  
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_bio');
        $CI->db->where('fieldset_bio_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_bio_id"] = get_object_property($fieldset, "fieldset_bio_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            
            $form["first_name"] = get_object_property($fieldset, "first_name");
            $form["last_name"] = get_object_property($fieldset, "last_name");
            $form["institution"] = get_object_property($fieldset, "institution");
            $form["position"] = get_object_property($fieldset, "position");
            $form["degree_line"] = get_object_property($fieldset, "degree_line");
            $form["bio"] = get_object_property($fieldset, "bio");
            $form["full_image"] = get_object_property($fieldset, "full_image");
            $form["thumbnail"] = get_object_property($fieldset, "thumbnail");
            
        }
        return $form;                    
    }
     
}