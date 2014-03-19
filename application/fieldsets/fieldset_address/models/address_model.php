<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class address_model extends base_fieldset_model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "address";
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
        if(get_if_set($form, "latitude")==""){
            $form["latitude"] = null;
        }
        if(get_if_set($form, "longitude")==""){
            $form["longitude"] = null;
        }
        
        $CI->load->database();        
        $CI->db->where('fieldset_address_id', $fieldset_id);
        $CI->db->update('fieldset_address', $form);  
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_address');
        $CI->db->where('fieldset_address_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_address_id"] = get_object_property($fieldset, "fieldset_address_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            
            $form["name"] = get_object_property($fieldset, "name");
            $form["description"] = get_object_property($fieldset, "description");
            $form["address_line_1"] = get_object_property($fieldset, "address_line_1");
            $form["address_line_2"] = get_object_property($fieldset, "address_line_2");
            $form["city"] = get_object_property($fieldset, "city");
            $form["state"] = get_object_property($fieldset, "state");
            $form["postal_code"] = get_object_property($fieldset, "postal_code");
            $form["country"] = get_object_property($fieldset, "country");
            $form["latitude"] = get_object_property($fieldset, "latitude");
            $form["longitude"] = get_object_property($fieldset, "longitude");
            
        }
        return $form;                    
    }   
}