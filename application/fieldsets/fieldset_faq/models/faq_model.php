<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class faq_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "faq";
        $this->auxillary_tables = array("fieldset_faq_data");
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
        
        // Manage Faqs
        $CI->db->select('fieldset_faq_data.fieldset_faq_data_id');                    
        $CI->db->from('fieldset_faq_data');
        $CI->db->where("fieldset_faq_data.fieldset_faq_id",$fieldset_id);
      
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_faq_data){
                if(!isset($form["fieldset_faq_data"]) || !isset($form["fieldset_faq_data"][$fieldset_faq_data->fieldset_faq_data_id])){
                    $CI->db->where('fieldset_faq_data.fieldset_faq_data_id', $fieldset_faq_data->fieldset_faq_data_id);
                    $CI->db->where('fieldset_faq_id',$fieldset_id);
                    $CI->db->delete('fieldset_faq_data');                
                }
            }
        }
        
        if (isset($form["fieldset_faq_data"]) && is_array($form["fieldset_faq_data"])) {
            $rank=0;
            if($form["order"]!=""){
                $order_array = explode(",",$form["order"]);
            }else{
                $order_array = null;
            }            
            foreach($form["fieldset_faq_data"] as $fieldset_faq_data) {        
                    if($order_array){
                        $rank = array_search(get_if_set($fieldset_faq_data,"fieldset_faq_data_id"), $order_array);
                    }
                    $rank++;                
                
                    $CI->db->select('*');
                    $CI->db->from('fieldset_faq_data');
                    $CI->db->where('fieldset_faq_data_id',$fieldset_faq_data["fieldset_faq_data_id"]);
                    $CI->db->where('fieldset_faq_id',$fieldset_id);
                    $query = $CI->db->get();
                    
                    $fieldset_faq_data["question"] = get_if_set($fieldset_faq_data, "question", null);
                    $fieldset_faq_data["answer"] = get_if_set($fieldset_faq_data, "answer", null);
                    
                    if ($query->num_rows()== 0) {
                        $CI->db->insert("fieldset_faq_data", 
                                array(
                                    "fieldset_faq_id"=>$fieldset_id,
                                    "question"=>$fieldset_faq_data["question"],
                                    "answer"=>$fieldset_faq_data["answer"],
                                    "rank"=>$rank
                                    )
                                );
                    }else{
                        $CI->db->where('fieldset_faq_data_id',$fieldset_faq_data["fieldset_faq_data_id"]);
                        $CI->db->where('fieldset_faq_id',$fieldset_id);
                        $CI->db->update("fieldset_faq_data", 
                                    array(
                                        "question"=>$fieldset_faq_data["question"],
                                        "answer"=>$fieldset_faq_data["answer"],
                                        "rank"=>$rank
                                        )
                                );
                    }
            }   
        }
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        
        $CI->db->select('fieldset_faq_data.fieldset_faq_data_id,fieldset_faq_data.question,fieldset_faq_data.answer');
        $CI->db->from('fieldset_faq_data as fieldset_faq_data');
        $CI->db->where('fieldset_faq_data.fieldset_faq_id', $fieldset_id);
        $CI->db->order_by('fieldset_faq_data.rank','ASC');
        
        $form["fieldset_faq_id"] = $fieldset_id;
        $form["fieldset_faq_data"] = array();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                $arr_elm["fieldset_faq_data_id"] = get_object_property($result, "fieldset_faq_data_id");
                $arr_elm["question"] = get_object_property($result, "question");
                $arr_elm["answer"] =  get_object_property($result, "answer");
                $form["fieldset_faq_data"][$arr_elm["fieldset_faq_data_id"]] = $arr_elm;
            }
        }
        return $form;                    
    }
    
}