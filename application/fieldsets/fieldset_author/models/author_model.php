<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class author_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "author";
        $this->auxillary_tables = array("fieldset_author_mapping");
    }

    function validate($form) {
        $validation = array();

        
        if (isset($form["fieldset_author_mapping"])) {
            foreach ($form["fieldset_author_mapping"] as $key => $arr_elm) {
                if(get_if_set($arr_elm,"fieldset_author_data_id")==""){
                    $validation["fields"]["fieldset_author_mapping"][$key]["fieldset_author_data_id"]["type"] = "error";
                    $validation["fields"]["fieldset_author_mapping"][$key]["fieldset_author_data_id"]["message"] = "Author is required.";                    
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
        $CI->db->select('fieldset_author_mapping.fieldset_author_mapping_id, fieldset_author_mapping.fieldset_author_id');                    
        $CI->db->from('fieldset_author_mapping');
        $CI->db->where("fieldset_author_mapping.fieldset_author_id",$fieldset_id);
        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_author_mapping){
                if(!isset($form["fieldset_author_mapping"]) || !isset($form["fieldset_author_mapping"][$fieldset_author_mapping->fieldset_author_mapping_id])){
                    $CI->db->where('fieldset_author_mapping.fieldset_author_mapping_id', $fieldset_author_mapping->fieldset_author_mapping_id);
                    $CI->db->where('fieldset_author_mapping.fieldset_author_id', $fieldset_author_mapping->fieldset_author_id);
                    $CI->db->delete('fieldset_author_mapping');                
                }
            }
        }
        
        if (isset($form["fieldset_author_mapping"]) && is_array($form["fieldset_author_mapping"])) {
            $rank=0;
            if($form["order"]!=""){
                $order_array = explode(",",$form["order"]);
            }else{
                $order_array = null;
            }
            foreach($form["fieldset_author_mapping"] as $fieldset_author_mapping) {
                    if($order_array){
                        $rank = array_search(get_if_set($fieldset_author_mapping,"fieldset_author_mapping_id"), $order_array);
                    }
                    $rank++;
                    
                    
                    $CI->db->select('*');
                    $CI->db->from('fieldset_author_mapping');
                    $CI->db->where('fieldset_author_mapping_id',get_if_set($fieldset_author_mapping,"fieldset_author_mapping_id"));
                    $CI->db->where('fieldset_author_id',$fieldset_id);
                    $query = $CI->db->get();
                    if ($query->num_rows()== 0) {
                        $CI->db->insert("fieldset_author_mapping", 
                                array(
                                    "fieldset_author_id"=>$fieldset_id,
                                    "fieldset_author_data_id"=>get_if_set($fieldset_author_mapping,"fieldset_author_data_id"),
                                    "display_name"=>get_if_set($fieldset_author_mapping,"display_name"),
                                    "position"=>get_if_set($fieldset_author_mapping,"position"),
                                    "rank"=>$rank
                                    )
                                );
                    }else{
                        $CI->db->where('fieldset_author_mapping_id',get_if_set($fieldset_author_mapping,"fieldset_author_mapping_id"));
                        $CI->db->where('fieldset_author_id',$fieldset_id);                        
                        $CI->db->update("fieldset_author_mapping",
                                array(
                                    "fieldset_author_data_id"=>get_if_set($fieldset_author_mapping,"fieldset_author_data_id"),
                                    "display_name"=>get_if_set($fieldset_author_mapping,"display_name"),
                                    "position"=>get_if_set($fieldset_author_mapping,"position"),
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
    
        //GET VERSION ID FIRST, ITS NEEDED
        $CI->db->select('fieldset_author.fieldset_author_id, fieldset_author.version_id');
        $CI->db->from('fieldset_author');
        $CI->db->where('fieldset_author.fieldset_author_id', $fieldset_id);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_author_id"] = get_object_property($fieldset, "fieldset_author_id");
            $form["version_id"] = get_object_property($fieldset, "version_id");                
        
        
            $CI->db->select('fieldset_author_mapping.fieldset_author_mapping_id, fieldset_author_mapping.fieldset_author_data_id, fieldset_author_data.display_name as author_data_display_name, fieldset_author_mapping.display_name, fieldset_author_mapping.position');
            $CI->db->from('fieldset_author_mapping as fieldset_author_mapping');
            $CI->db->join('fieldset_author_data as fieldset_author_data','fieldset_author_mapping.fieldset_author_data_id = fieldset_author_data.fieldset_author_data_id','INNER');
            $CI->db->where('fieldset_author_mapping.fieldset_author_id', $fieldset_id);
            $CI->db->order_by('fieldset_author_mapping.rank','ASC');

            $form["fieldset_author_id"] = $fieldset_id;
            $form["fieldset_author_mapping"] = array();
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                foreach($query->result() as $result){
                    $arr_elm["fieldset_author_mapping_id"] = get_object_property($result, "fieldset_author_mapping_id");
                    $arr_elm["fieldset_author_data_id"] = get_object_property($result, "fieldset_author_data_id");
                    $arr_elm["display_name"] = get_object_property($result, "display_name");
                    $arr_elm["author_data_display_name"] = get_object_property($result, "author_data_display_name");
                    $arr_elm["position"] = get_object_property($result, "position");
                    $form["fieldset_author_mapping"][$arr_elm["fieldset_author_mapping_id"]] = $arr_elm;
                }
            }
        }
        return $form;                    
    }

    function get_author_by_query($term){
        $authors = array();
        $CI =& get_instance();
        $CI->load->database();

        
        $CI->db->select('fieldset_author_data.fieldset_author_data_id, fieldset_author_data.display_name,fieldset_author_data.position');
        $CI->db->from('fieldset_author_data');
        $CI->db->where("fieldset_author_data.display_name LIKE '%$term%'");
        $query = $CI->db->get();        
        
        if ($query->num_rows() > 0) {
            foreach($query->result() as $row){
                $authors[] = array(
                    "id" => $row->fieldset_author_data_id,
                    "label" => $row->display_name,
                    "display_name" => $row->display_name,
                    "position" => $row->position
                );
            }
        }        
        return $authors;
        
    }
   
    function get_fieldset_author_data_list(){
        $this->load->database();
        $this->db->select("fieldset_author_data.*, fieldset_author_mapping.fieldset_author_mapping_id");
        $this->db->from("fieldset_author_data");
        $this->db->join("fieldset_author_mapping","fieldset_author_mapping.fieldset_author_data_id = fieldset_author_data.fieldset_author_data_id","LEFT");
        $this->db->order_by("fieldset_author_data.last_name ASC,fieldset_author_data.first_name ASC");
        $this->db->group_by("fieldset_author_data.fieldset_author_data_id");
        $query = $this->db->get();
        return $query;
    }
    
    function validate_author_data($form) {
        $validation = array();
    
        if(get_if_set($form,"display_name")==""){
            $validation["fields"]["display_name"]["message"]["type"] = "error";
            $validation["fields"]["display_name"]["message"]["message"] = "Display name is required.";                    
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

    function author_array_to_db($id, $form){
        if(isset($form)){
            $CI = & get_instance();
            $CI->load->database();        
            if($id){
                $CI->db->where('fieldset_author_data_id', $id);
                $CI->db->update('fieldset_author_data', $form);              
            }else{
                $CI->db->insert('fieldset_author_data', $form);              
            }
        }
    } 
    
    function author_db_to_array($id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
    
        //GET VERSION ID FIRST, ITS NEEDED
        $CI->db->select('*');
        $CI->db->from('fieldset_author_data');
        $CI->db->where('fieldset_author_data.fieldset_author_data_id', $id);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $form["fieldset_author_data_id"] = $id;
            $form["key"] = get_object_property($row, "key");
            $form["display_name"] = get_object_property($row, "display_name");                
            $form["first_name"] = get_object_property($row, "first_name");
            $form["last_name"] = get_object_property($row, "last_name");
            $form["position"] = get_object_property($row, "position");
            $form["description"] = get_object_property($row, "description");
            
        }
        return $form;
    } 
    
    function delete_fieldset_author_data($fieldset_author_data_id){
        $CI = & get_instance();
        $CI->load->database();        
        $CI->db->where('fieldset_author_data_id',$fieldset_author_data_id);
        $CI->db->delete('fieldset_author_data');
    }
    
}