<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class categories_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "categories";
        $this->auxillary_tables = array("fieldset_categories_mapping");
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
        $CI->db->select('fieldset_categories_data.fieldset_categories_data_id, fieldset_categories_data.group_key,fieldset_categories_mapping.fieldset_categories_id');                    
        $CI->db->from('fieldset_categories_data');
        $CI->db->join("fieldset_categories_mapping","fieldset_categories_mapping.fieldset_categories_data_id = fieldset_categories_data.fieldset_categories_data_id","INNER");
        $CI->db->where("fieldset_categories_mapping.fieldset_categories_id",$fieldset_id);
        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_categories_data){
                if(!isset($form["categories"]) || !isset($form["categories"][$fieldset_categories_data->fieldset_categories_data_id])){
                    $CI->db->where('fieldset_categories_mapping.fieldset_categories_data_id', $fieldset_categories_data->fieldset_categories_data_id);
                    $CI->db->where('fieldset_categories_mapping.fieldset_categories_id', $fieldset_categories_data->fieldset_categories_id);
                    $CI->db->delete('fieldset_categories_mapping');                
                }
            }
        }
        
        if (isset($form["category"]) && is_array($form["category"])) {
            foreach($form["category"] as $fieldset_categories_data_id => $value) {
                $CI->db->select('*');
                $CI->db->from('fieldset_categories_mapping');
                $CI->db->where('fieldset_categories_data_id',$fieldset_categories_data_id);
                $CI->db->where('fieldset_categories_id',$fieldset_id);
                $query = $CI->db->get();
                if ($query->num_rows()== 0) {
                    $CI->db->insert("fieldset_categories_mapping", array("fieldset_categories_id"=>$fieldset_id,"fieldset_categories_data_id"=> $fieldset_categories_data_id));
                }
            }   
        }
        
        $values = array();
        if (isset($form["primary_category"])) {
            $values["primary_fieldset_category_data_id"] = $form["primary_category"];
        } else {
            $values["primary_fieldset_category_data_id"] = null;
        }
        $CI->db->where('fieldset_categories_id', $fieldset_id);
        $CI->db->update('fieldset_categories', $values);  
        
        $this->index($fieldset_id);
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();    
        $form["fieldset_categories_id"] = $fieldset_id;
        
        $CI = & get_instance();
        $CI->load->database();
        
        $CI->db->select('fieldset_categories.primary_fieldset_category_data_id');
        $CI->db->from('fieldset_categories');
        $CI->db->where('fieldset_categories_id', $fieldset_id);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                 $form["primary_category"] = $result->primary_fieldset_category_data_id;
            }
        }
        
        $CI->db->select('fieldset_categories_data.fieldset_categories_data_id');
        $CI->db->from('fieldset_categories_mapping as fieldset_categories_mapping');
        $CI->db->join('fieldset_categories_data as fieldset_categories_data', "fieldset_categories_data.fieldset_categories_data_id = fieldset_categories_mapping.fieldset_categories_data_id","join");
        $CI->db->where('fieldset_categories_mapping.fieldset_categories_id', $fieldset_id);
        
        $form["category"] = array();
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                $form["category"][$result->fieldset_categories_data_id] = "true";
            }
        }
        return $form;
    }
    
    function index($fieldset_id) {
        
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select("fieldset_categories_data.fieldset_categories_data_id, category_name, key");
        $CI->db->from('fieldset_categories_mapping');
        $CI->db->join('fieldset_categories_data', 'fieldset_categories_data.fieldset_categories_data_id = fieldset_categories_mapping.fieldset_categories_data_id');
        $CI->db->where('fieldset_categories_id', $fieldset_id);
        
        $query = $CI->db->get();
        
        $id_index = "|*|";
        $name_index = "|*|";
        $key_index = "|*|";
        
        foreach ($query->result() as $row) {
            $id_index .= $row->fieldset_categories_data_id . "|*|";
            $name_index .= $row->category_name . "|*|";
            $key_index .= $row->key . "|*|";
        }
        
        $values = array();
        $fieldset_config = $this->get_fieldset_config_row($fieldset_id);
        $values["fieldset_key"] = $fieldset_config->key;
        $values["id_index"] = $id_index;
        $values["name_index"] = $name_index;
        $values["key_index"] = $key_index;
        $CI->db->where("fieldset_categories_id", $fieldset_id);
        $CI->db->update("fieldset_categories", $values);
        
    }
    
    // This is used whenever a category is updated / deleted to refresh the index
    function index_for_a_category($fieldset_category_data_id) {
        
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select("fieldset_categories_id");
        $CI->db->from('fieldset_categories_mapping');
        $CI->db->where('fieldset_categories_data_id', $fieldset_category_data_id);
        
        $query = $CI->db->get();
        
        foreach ($query->result() as $row) {
            $this->index($row->fieldset_categories_id);
        }
        
    }

    function get_categories_group_key($fieldset_id){
        $group_keys = array();
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_config.parameters');
        $CI->db->from('fieldset_categories');
        $this->db->join('fieldset_config', 'fieldset_categories.fieldset_config_id = fieldset_config.fieldset_config_id');
        $CI->db->where('fieldset_categories.fieldset_categories_id', $fieldset_id);
        $query = $CI->db->get();
        $group = array();
        if ($query->num_rows() > 0) {
            parse_str($query->row('parameters'), $parameters);
            foreach ($parameters["group"] as $key=>$value) {
                $group["key"] = $key;
                $group["name"] = $value;
            }
        }
        return $group;
    }
    function get_categories($group_key){
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_categories_data');
        $CI->db->where('group_key', $group_key);
        $CI->db->order_by('rank asc');
        $query = $CI->db->get();
        
        $category_array = array();
        
        foreach ($query->result() as $row) {
            $result = array();
            $result["fieldset_categories_data_id"] = $row->fieldset_categories_data_id;
            $result["parent_fieldset_categories_data_id"] = $row->parent_fieldset_categories_data_id;
            $result["category_name"] = $row->category_name;
            $result["key"] = $row->key;
            $result["rank"] = $row->rank;
            
            $key = $row->parent_fieldset_categories_data_id;
            if (!$key) { $key = -1; }
            $category_array[$key][] = $result;
        }
        
        return $category_array;
    }

    function categories_validate($form) {
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

    function categories_array_to_db($group,$form){
        $CI = & get_instance();
        $CI->load->database();                
        $now = new DateTime();
        $new_ids = array();
        $processed_id = array();
        if(isset($form["category"]) && is_array($form["category"])){
            foreach($form["category"] as $category){
                $category["group_key"] = $group["key"];
                $category["last_modified_at"] = $now->format("Y-m-d H:i:s");
                if($category["parent_fieldset_categories_data_id"] == ""){
                   $category["parent_fieldset_categories_data_id"] = null; 
                }
                
                if(!is_numeric($category["parent_fieldset_categories_data_id"])){
                    if(isset($new_ids[$category["parent_fieldset_categories_data_id"]])){
                        $category["parent_fieldset_categories_data_id"] = $new_ids[$category["parent_fieldset_categories_data_id"]];
                    }else{
                        //supposedly this will never happen
                        $category["parent_fieldset_categories_data_id"] = null;
                    }
                }
                
                if($category["status"] == "new"){
                    $id = $category["fieldset_categories_data_id"];
                    unset($category["fieldset_categories_data_id"]);
                    unset($category["status"]);
                    $CI->db->insert("fieldset_categories_data", $category);
                    $inserted_id = $CI->db->insert_id();
                    $new_ids[$id] = $inserted_id; 
                    $processed_id[] = $inserted_id; 
                }else{
                    $CI->db->where('fieldset_categories_data_id', $category["fieldset_categories_data_id"]);
                    $processed_id[] = $category["fieldset_categories_data_id"];
                    unset($category["fieldset_categories_data_id"]);
                    unset($category["status"]);
                    $CI->db->update('fieldset_categories_data', $category);                  
                    
                }
            }
        }
        
        $CI->db->where_not_in('fieldset_categories_data_id', $processed_id);
        $CI->db->where('group_key', $group["key"]);
        $CI->db->delete("fieldset_categories_data");

    }    
    
}