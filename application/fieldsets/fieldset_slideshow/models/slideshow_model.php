<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class slideshow_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "slideshow";
        $this->auxillary_tables = array("fieldset_slideshow_mapping");
    }

    function validate($form) {
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
        $CI->db->select('fieldset_slideshow_mapping.fieldset_file_data_id, fieldset_slideshow_mapping.fieldset_slideshow_id');                    
        $CI->db->from('fieldset_slideshow_mapping');
        $CI->db->where("fieldset_slideshow_mapping.fieldset_slideshow_id",$fieldset_id);
        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_slideshow_mapping){
                if(!isset($form["fieldset_file_data"]) || !isset($form["fieldset_file_data"][$fieldset_slideshow_mapping->fieldset_file_data_id])){
                    $CI->db->where('fieldset_slideshow_mapping.fieldset_file_data_id', $fieldset_slideshow_mapping->fieldset_file_data_id);
                    $CI->db->where('fieldset_slideshow_mapping.fieldset_slideshow_id', $fieldset_slideshow_mapping->fieldset_slideshow_id);
                    $CI->db->delete('fieldset_slideshow_mapping');                
                }
            }
        }
        
        if (isset($form["fieldset_file_data"]) && is_array($form["fieldset_file_data"])) {
            $rank=0;
            if($form["order"]!=""){
                $order_array = explode(",",$form["order"]);
            }else{
                $order_array = null;
            }
            
            foreach($form["fieldset_file_data"] as $fieldset_file_data_id) {
                if($fieldset_file_data_id !=""){
                    if($order_array){
                        $rank = array_search($fieldset_file_data_id, $order_array);
                    }
                    $rank++;
                    
                    $CI->db->select('*');
                    $CI->db->from('fieldset_slideshow_mapping');
                    $CI->db->where('fieldset_file_data_id',$fieldset_file_data_id);
                    $CI->db->where('fieldset_slideshow_id',$fieldset_id);
                    $query = $CI->db->get();
                    if ($query->num_rows()== 0) {
                        $CI->db->insert("fieldset_slideshow_mapping", array("fieldset_slideshow_id"=>$fieldset_id,"fieldset_file_data_id"=>$fieldset_file_data_id,"rank"=>$rank));
                    }else{
                        $CI->db->where('fieldset_file_data_id',$fieldset_file_data_id);
                        $CI->db->where('fieldset_slideshow_id',$fieldset_id);                        
                        $CI->db->update("fieldset_slideshow_mapping", array("rank"=>$rank));
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
        
        //GET VERSION ID FIRST, ITS NEEDED
        $CI->db->select('fieldset_slideshow.fieldset_slideshow_id, fieldset_slideshow.version_id');
        $CI->db->from('fieldset_slideshow');
        $CI->db->where('fieldset_slideshow.fieldset_slideshow_id', $fieldset_id);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_slideshow_id"] = get_object_property($fieldset, "fieldset_slideshow_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");        
        
            $CI->db->select('fieldset_slideshow_mapping.fieldset_file_data_id');
            $CI->db->from('fieldset_slideshow_mapping as fieldset_slideshow_mapping');
            $CI->db->where('fieldset_slideshow_mapping.fieldset_slideshow_id', $form["fieldset_slideshow_id"]);
            $CI->db->order_by('fieldset_slideshow_mapping.rank','ASC');


            $form["fieldset_file_data"] = array();
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                foreach($query->result() as $result){
                    $arr_elm["fieldset_file_data_id"] = get_object_property($result, "fieldset_file_data_id");
                    $form["fieldset_file_data"][$arr_elm["fieldset_file_data_id"]] = $arr_elm;
                }
            }
        }
        return $form;                    
    }

}