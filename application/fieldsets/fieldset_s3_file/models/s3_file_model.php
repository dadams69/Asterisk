<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class s3_file_model extends base_fieldset_model {

    
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "s3_file";
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
        $CI->db->where('fieldset_s3_file_id', $fieldset_id);
        $CI->db->update('fieldset_s3_file', $form);  
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_s3_file');
        $CI->db->where('fieldset_s3_file_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_s3_file_id"] = get_object_property($fieldset, "fieldset_s3_file_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            $form["filename"] = get_object_property($fieldset, "filename");
        }
        return $form;                    
    }

    function get_parameters($fieldset_id){
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_config.parameters');
        $CI->db->from('fieldset_s3_file');
        $this->db->join('fieldset_config', 'fieldset_s3_file.fieldset_config_id = fieldset_config.fieldset_config_id');
        $CI->db->where('fieldset_s3_file.fieldset_s3_file_id', $fieldset_id);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            parse_str($query->row('parameters'), $parameters);
            return $parameters;
        }else{
            return array();
        }
    }
    
}