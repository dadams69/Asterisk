<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class publish_date_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "publish_date";
        $this->auxillary_tables = array("fieldset_publish_date_data");
    }

    function validate($form) {
        $validation = array();
        
        if (isset($form["fieldset_publish_date_data"])) {
            foreach ($form["fieldset_publish_date_data"] as $key => $arr_elm) {
                if(get_if_set($arr_elm,"type")==""){
                    $validation["fields"]["fieldset_publish_date_data"][$key]["type"]["type"] = "error";
                    $validation["fields"]["fieldset_publish_date_data"][$key]["type"]["message"] = "Type is required.";                    
                }
                
                if (get_if_set($arr_elm, "publish_date") == ""){
                    $validation["fields"]["fieldset_publish_date_data"][$key]["publish_date"]["type"] = "error";
                    $validation["fields"]["fieldset_publish_date_data"][$key]["publish_date"]["message"] = "Publish Date is required.";                                        
                }elseif(!getdate_if_set("m/d/Y H:i:s",$arr_elm, "publish_date",null)) {
                    $validation["fields"]["fieldset_publish_date_data"][$key]["publish_date"]["type"] = "error";
                    $validation["fields"]["fieldset_publish_date_data"][$key]["publish_date"]["message"] = "Publish Date is invalid.";                    
                }           
            }
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
        $CI->load->database();                
        $CI->db->select('fieldset_publish_date_data.fieldset_publish_date_data_id');                    
        $CI->db->from('fieldset_publish_date_data');
        $CI->db->where("fieldset_publish_date_data.fieldset_publish_date_id",$fieldset_id);
        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_publish_date_data){
                if(!isset($form["fieldset_publish_date_data"]) || !isset($form["fieldset_publish_date_data"][$fieldset_publish_date_data->fieldset_publish_date_data_id])){
                    $CI->db->where('fieldset_publish_date_data.fieldset_publish_date_data_id', $fieldset_publish_date_data->fieldset_publish_date_data_id);
                    $CI->db->where('fieldset_publish_date_id',$fieldset_id);
                    $CI->db->delete('fieldset_publish_date_data');                
                }
            }
        }
        
        if (isset($form["fieldset_publish_date_data"]) && is_array($form["fieldset_publish_date_data"])) {
            
            foreach($form["fieldset_publish_date_data"] as $fieldset_publish_date_data) {        
                    $CI->db->select('*');
                    $CI->db->from('fieldset_publish_date_data');
                    $CI->db->where('fieldset_publish_date_data_id',$fieldset_publish_date_data["fieldset_publish_date_data_id"]);
                    $CI->db->where('fieldset_publish_date_id',$fieldset_id);
                    $query = $CI->db->get();
                    
                    $fieldset_publish_date_data["publish_date"] = convert_string_date_format("m/d/Y H:i:s", "Y-m-d H:i:s", $fieldset_publish_date_data["publish_date"], null);
                    
                    if ($query->num_rows()== 0) {
                        
                        $CI->db->insert("fieldset_publish_date_data", array("fieldset_publish_date_id"=>$fieldset_id,"type"=>$fieldset_publish_date_data["type"],"publish_date"=>$fieldset_publish_date_data["publish_date"],"note"=>$fieldset_publish_date_data["note"]));
                    }else{
                        $CI->db->where('fieldset_publish_date_data_id',$fieldset_publish_date_data["fieldset_publish_date_data_id"]);
                        $CI->db->where('fieldset_publish_date_id',$fieldset_id);
                        $CI->db->update("fieldset_publish_date_data", array("type"=>$fieldset_publish_date_data["type"],"publish_date"=>$fieldset_publish_date_data["publish_date"],"note"=>$fieldset_publish_date_data["note"]));
                    }
            }   
        }
        $this->index($fieldset_id);
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_publish_date_data.fieldset_publish_date_data_id,fieldset_publish_date_data.type,fieldset_publish_date_data.publish_date,fieldset_publish_date_data.note');
        $CI->db->from('fieldset_publish_date_data as fieldset_publish_date_data');
        $CI->db->where('fieldset_publish_date_data.fieldset_publish_date_id', $fieldset_id);
        $CI->db->order_by('fieldset_publish_date_data.publish_date','ASC');
        
        $form["fieldset_publish_date_id"] = $fieldset_id;
        $form["fieldset_publish_date_data"] = array();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                $arr_elm["fieldset_publish_date_data_id"] = get_object_property($result, "fieldset_publish_date_data_id");
                $arr_elm["type"] = get_object_property($result, "type");
                $arr_elm["publish_date"] = convert_string_date_format("Y-m-d H:i:s","m/d/Y H:i:s",get_object_property($result, "publish_date"));
                $arr_elm["note"] = get_object_property($result, "note");
                $form["fieldset_publish_date_data"][$arr_elm["fieldset_publish_date_data_id"]] = $arr_elm;
            }
        }
        return $form;                    
    }
  
    function index($fieldset_id) {
        
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select("fieldset_publish_date_data.publish_date");
        $CI->db->from('fieldset_publish_date_data');
        $CI->db->where('fieldset_publish_date_data.fieldset_publish_date_id', $fieldset_id);
        
        $query = $CI->db->get();
        
        $index_first_date = null;
        $index_last_date = null;
        
        foreach ($query->result() as $row) {
            $publish_date = DateTime::createFromFormat("Y-m-d H:i:s", get_object_property($row, "publish_date",null));
            if($publish_date && (!$index_first_date || $publish_date < $index_first_date)){
                $index_first_date = $publish_date;
            }
            if($publish_date && (!$index_last_date || $publish_date > $index_last_date)){
                $index_last_date = $publish_date;
            }
        }
        
        $values = array();
        $values["index_first_date"] = ($index_first_date)?$index_first_date->format("Y-m-d H:i:s"):null;
        $values["index_last_date"] = ($index_last_date)?$index_last_date->format("Y-m-d H:i:s"):null;
        $CI->db->where("fieldset_publish_date_id", $fieldset_id);
        $CI->db->update("fieldset_publish_date", $values);
        
    }
    
    // This is used whenever a category is updated / deleted to refresh the index
    function index_for_a_tag($fieldset_publish_date_data_id) {
        
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select("fieldset_publish_date_id");
        $CI->db->from('fieldset_publish_date_data');
        $CI->db->where('fieldset_publish_date_data_id', $fieldset_publish_date_data_id);
        
        $query = $CI->db->get();
        
        foreach ($query->result() as $row) {
            $this->index($row->fieldset_publish_date_id);
        }
        
    }
  
}