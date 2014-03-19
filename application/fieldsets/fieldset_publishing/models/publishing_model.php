<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class publishing_model extends base_fieldset_model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "publishing";
    }

    function validate($form){
        $validation = array();
        if (get_if_set($form, "key") == "") {
            $validation["fields"]["key"]["message"]["type"] = "error";
            $validation["fields"]["key"]["message"]["text"] = "Key is required.";
        }        
        if (get_if_set($form, "active") != "0" && get_if_set($form, "active") != "1") {
            $validation["fields"]["active"]["message"]["type"] = "error";
            $validation["fields"]["active"]["message"]["text"] = "Status is required.";
        } 
        
        if ( get_if_set($form, "rank") != "" && !is_numeric(get_if_set($form, "rank"))) {
            $validation["fields"]["rank"]["message"]["type"] = "error";
            $validation["fields"]["rank"]["message"]["text"] = "Rank must be an integer.";
        } 
        
        if (get_if_set($form, "publish_date") !="" && !getdate_if_set("m/d/Y H:i:s",$form, "publish_date",null)) {
            $validation["fields"]["publish_date"]["message"]["type"] = "error";
            $validation["fields"]["publish_date"]["message"]["text"] = "Publish date is invalid.";
        }           
        if (get_if_set($form, "expiration_date") !="" && !getdate_if_set("m/d/Y H:i:s",$form, "expiration_date",null)) {
            $validation["fields"]["expiration_date"]["message"]["type"] = "error";
            $validation["fields"]["expiration_date"]["message"]["text"] = "Expiration date invalid.";
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
    
    function array_to_db($fieldset_id,$form){
        $CI = & get_instance();
        
        $form["publish_date"] = convert_string_date_format("m/d/Y H:i:s", "Y-m-d H:i:s", $form["publish_date"], null);
        $form["expiration_date"] = convert_string_date_format("m/d/Y H:i:s", "Y-m-d H:i:s", $form["expiration_date"], null);
        if (get_if_set($form, "rank") == "") {
            $form["rank"] = null;
        } else {
            $rank = intval(get_if_set($form, "rank"));
            $form["rank"] = $rank;
        }
        $CI->load->database();        
        $CI->db->where('fieldset_publishing_id', $fieldset_id);
        $CI->db->update('fieldset_publishing', $form);  
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_publishing');
        $CI->db->where('fieldset_publishing_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_publishing_id"] = get_object_property($fieldset, "fieldset_publishing_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            $form["key"] = get_object_property($fieldset, "key");
            $form["package"] = get_object_property($fieldset, "package");
            $form["rank"] = get_object_property($fieldset, "rank");
            $form["active"] = get_object_property($fieldset, "active");
            $form["publish_date"] = convert_string_date_format("Y-m-d H:i:s","m/d/Y H:i:s",get_object_property($fieldset, "publish_date"));
            $form["expiration_date"] = convert_string_date_format("Y-m-d H:i:s","m/d/Y H:i:s",get_object_property($fieldset, "expiration_date"));
        }
        return $form;                    
    }
    
}