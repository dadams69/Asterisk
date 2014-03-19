<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class related_content_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "related_content";
        $this->auxillary_tables = array("fieldset_related_content_mapping");
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
        $CI->db->select('fieldset_related_content_mapping.content_id, fieldset_related_content_mapping.fieldset_related_content_id');                    
        $CI->db->from('fieldset_related_content_mapping');
        $CI->db->where("fieldset_related_content_mapping.fieldset_related_content_id",$fieldset_id);
        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_related_content_mapping){
                if(!isset($form["content"]) || !isset($form["content"][$fieldset_related_content_mapping->content_id])){
                    $CI->db->where('fieldset_related_content_mapping.content_id', $fieldset_related_content_mapping->content_id);
                    $CI->db->where('fieldset_related_content_mapping.fieldset_related_content_id', $fieldset_related_content_mapping->fieldset_related_content_id);
                    $CI->db->delete('fieldset_related_content_mapping');                
                }
            }
        }
        
        if (isset($form["content"]) && is_array($form["content"])) {
            $rank=0;
            if($form["order"]!=""){
                $order_array = explode(",",$form["order"]);
            }else{
                $order_array = null;
            }
            foreach($form["content"] as $content_id) {
                    if($order_array){
                        $rank = array_search($content_id, $order_array);
                    }
                    $rank++;
                    
                    $CI->db->select('*');
                    $CI->db->from('fieldset_related_content_mapping');
                    $CI->db->where('content_id',$content_id);
                    $CI->db->where('fieldset_related_content_id',$fieldset_id);
                    $query = $CI->db->get();
                    if ($query->num_rows()== 0) {
                        $CI->db->insert("fieldset_related_content_mapping", array("fieldset_related_content_id"=>$fieldset_id,"content_id"=>$content_id,"rank"=>$rank));
                    }else{
                        $CI->db->where('content_id',$content_id);
                        $CI->db->where('fieldset_related_content_id',$fieldset_id);                        
                        $CI->db->update("fieldset_related_content_mapping", array("rank"=>$rank));
                    }
            }   
        }

        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_related_content_mapping.content_id, content.name');
        $CI->db->from('fieldset_related_content_mapping as fieldset_related_content_mapping');
        $CI->db->join('content as content', "content.content_id = fieldset_related_content_mapping.content_id","join");
        $CI->db->where('fieldset_related_content_mapping.fieldset_related_content_id', $fieldset_id);
        $CI->db->order_by('fieldset_related_content_mapping.rank','ASC');
        
        $form["fieldset_related_content_id"] = $fieldset_id;
        $form["content"] = array();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                $arr_elm["content_id"] = get_object_property($result, "content_id");
                $arr_elm["name"] = get_object_property($result, "name");
                $form["content"][$arr_elm["content_id"]] = $arr_elm;
            }
        }
        return $form;                    
    }
 
    function get_content_by_query($term, $existing_content_id){
        $content = array();
        $CI =& get_instance();
        $CI->load->database();

        
        $CI->db->select('content.content_id, content.name');
        $CI->db->from('content');
        $CI->db->where("content.name LIKE '%$term%'");
        $CI->db->where_not_in("content.content_id", explode(",", $existing_content_id));
        $query = $CI->db->get();        
        
        if ($query->num_rows() > 0) {
            foreach($query->result() as $row){
                $content[] = array(
                    "id" => $row->content_id,
                    "name" => $row->name
                        );
            }
        }        
        return $content;
        
    }
    
}