<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class event_recap_model extends base_fieldset_model {

    
    
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "event_recap";
        $this->auxillary_tables = array("fieldset_event_recap_mapping");
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
        $CI->db->where('fieldset_event_recap_id', $fieldset_id);
        if(isset($form["slideshow"])){
            $slideshow_form = $form["slideshow"];
            unset($form["slideshow"]);
        }else{
            $slideshow_form = array();
        }
        if(isset($form["show_recap"])){
            $form["show_recap"] = true;
        }else{
            $form["show_recap"] = false;
        }
        $CI->db->update('fieldset_event_recap', $form);  
        
        $CI->db->select('fieldset_event_recap_mapping.fieldset_file_data_id, fieldset_event_recap_mapping.fieldset_event_recap_id');                    
        $CI->db->from('fieldset_event_recap_mapping');
        $CI->db->where("fieldset_event_recap_mapping.fieldset_event_recap_id",$fieldset_id);
        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_event_recap_mapping){
                if(!isset($slideshow_form["fieldset_file_data"]) || !isset($slideshow_form["fieldset_file_data"][$fieldset_event_recap_mapping->fieldset_file_data_id])){
                    $CI->db->where('fieldset_event_recap_mapping.fieldset_file_data_id', $fieldset_event_recap_mapping->fieldset_file_data_id);
                    $CI->db->where('fieldset_event_recap_mapping.fieldset_event_recap_id', $fieldset_event_recap_mapping->fieldset_event_recap_id);
                    $CI->db->delete('fieldset_event_recap_mapping');                
                }
            }
        }
        
        if (isset($slideshow_form["fieldset_file_data"]) && is_array($slideshow_form["fieldset_file_data"])) {
            $rank=0;
            if($slideshow_form["order"]!=""){
                $order_array = explode(",",$slideshow_form["order"]);
            }else{
                $order_array = null;
            }
            
            foreach($slideshow_form["fieldset_file_data"] as $fieldset_file_data_id) {
                if($fieldset_file_data_id !=""){
                    if($order_array){
                        $rank = array_search($fieldset_file_data_id, $order_array);
                    }
                    $rank++;
                    
                    $CI->db->select('*');
                    $CI->db->from('fieldset_event_recap_mapping');
                    $CI->db->where('fieldset_file_data_id',$fieldset_file_data_id);
                    $CI->db->where('fieldset_event_recap_id',$fieldset_id);
                    $query = $CI->db->get();
                    if ($query->num_rows()== 0) {
                        $CI->db->insert("fieldset_event_recap_mapping", array("fieldset_event_recap_id"=>$fieldset_id,"fieldset_file_data_id"=>$fieldset_file_data_id,"rank"=>$rank));
                    }else{
                        $CI->db->where('fieldset_file_data_id',$fieldset_file_data_id);
                        $CI->db->where('fieldset_event_recap_id',$fieldset_id);                        
                        $CI->db->update("fieldset_event_recap_mapping", array("rank"=>$rank));
                    }
                }
            }   
        }        
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_event_recap');
        $CI->db->where('fieldset_event_recap_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_event_recap_id"] = get_object_property($fieldset, "fieldset_event_recap_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");
            $form["show_recap"] = get_object_property($fieldset, "show_recap");
            $form["description"] = get_object_property($fieldset, "description");
            $form["vimeo_id"] = get_object_property($fieldset, "vimeo_id");
            $form["audio"] = get_object_property($fieldset, "audio");
            $form["link"] = get_object_property($fieldset, "link");
            
            $CI->db->select('fieldset_event_recap_mapping.fieldset_file_data_id');
            $CI->db->from('fieldset_event_recap_mapping as fieldset_event_recap_mapping');
            $CI->db->where('fieldset_event_recap_mapping.fieldset_event_recap_id', $form["fieldset_event_recap_id"]);
            $CI->db->order_by('fieldset_event_recap_mapping.rank','ASC');


            $form["slideshow"]["fieldset_file_data"] = array();
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                foreach($query->result() as $result){
                    $arr_elm["fieldset_file_data_id"] = get_object_property($result, "fieldset_file_data_id");
                    $form["slideshow"]["fieldset_file_data"][$arr_elm["fieldset_file_data_id"]] = $arr_elm;
                }
            }            
        }
        return $form;                    
    }

}