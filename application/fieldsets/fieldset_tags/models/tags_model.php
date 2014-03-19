<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class tags_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "tags";
        $this->auxillary_tables = array("fieldset_tags_mapping");
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
        $CI->db->select('fieldset_tags_data.fieldset_tags_data_id, fieldset_tags_data.group_key,fieldset_tags_mapping.fieldset_tags_id');                    
        $CI->db->from('fieldset_tags_data');
        $CI->db->join("fieldset_tags_mapping","fieldset_tags_mapping.fieldset_tags_data_id = fieldset_tags_data.fieldset_tags_data_id","INNER");
        $CI->db->where("fieldset_tags_mapping.fieldset_tags_id",$fieldset_id);
        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_tags_data){
                if(!isset($form["tags"]) || !isset($form["tags"][$fieldset_tags_data->group_key])  || !isset($form["tags"][$fieldset_tags_data->group_key]["existing"][$fieldset_tags_data->fieldset_tags_data_id])){
                    $CI->db->where('fieldset_tags_mapping.fieldset_tags_data_id', $fieldset_tags_data->fieldset_tags_data_id);
                    $CI->db->where('fieldset_tags_mapping.fieldset_tags_id', $fieldset_tags_data->fieldset_tags_id);
                    $CI->db->delete('fieldset_tags_mapping');                
                }
            }
        }
        
        
        if (isset($form["tags"]) && is_array($form["tags"])) {
            foreach($form["tags"] as $group_key => $group_elms) {

                if(isset($group_elms["existing"]) && is_array($group_elms["existing"])){
                    foreach($group_elms["existing"] as $existing_tag_data_id){
                        $CI->db->select('*');
                        $CI->db->from('fieldset_tags_mapping');
                        $CI->db->where('fieldset_tags_data_id',$existing_tag_data_id);
                        $CI->db->where('fieldset_tags_id',$fieldset_id);
                        $query = $CI->db->get();
                        if ($query->num_rows()== 0) {
                            $CI->db->insert("fieldset_tags_mapping", array("fieldset_tags_id"=>$fieldset_id,"fieldset_tags_data_id"=>$existing_tag_data_id));
                        }
                    }
                }
                    
                if(isset($group_elms["new"]) && is_array($group_elms["new"])){
                    foreach($group_elms["new"] as $new_tag_name){
                            $tag_key = $this->generate_new_tag_key($new_tag_name);
                            $CI->db->insert("fieldset_tags_data", array("tag_name"=>$new_tag_name,"key"=>$tag_key,"group_key"=>$group_key));
                            $fieldset_tags_data_id = $CI->db->insert_id();
                            $CI->db->insert("fieldset_tags_mapping", array("fieldset_tags_id"=>$fieldset_id,"fieldset_tags_data_id"=>$fieldset_tags_data_id));
                    }
                }
            }   
        }
        
        $this->delete_non_used_tags();
        
        $this->index($fieldset_id);
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_tags_data.fieldset_tags_data_id, fieldset_tags_data.tag_name, fieldset_tags_data.key, fieldset_tags_data.group_key');
        $CI->db->from('fieldset_tags_mapping as fieldset_tags_mapping');
        $CI->db->join('fieldset_tags_data as fieldset_tags_data', "fieldset_tags_data.fieldset_tags_data_id = fieldset_tags_mapping.fieldset_tags_data_id","join");
        $CI->db->where('fieldset_tags_mapping.fieldset_tags_id', $fieldset_id);
        
        $form["fieldset_tags_id"] = $fieldset_id;
        $form["tag"] = array();
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                $group_key = get_object_property($result, "group_key");
                $arr_elm["fieldset_tags_data_id"] = get_object_property($result, "fieldset_tags_data_id");
                $arr_elm["tag_name"] = get_object_property($result, "tag_name");
                $arr_elm["key"] = get_object_property($result, "key");
                $form["tag"][$group_key]["existing"][] = $arr_elm["fieldset_tags_data_id"];
                $form["tag"][$group_key]["existing_data"][$arr_elm["fieldset_tags_data_id"]] = $arr_elm;
            }
        }
        return $form;                    
    }

    function delete($fieldset_id) {
        parent::delete($fieldset_id);
        
        $this->delete_non_used_tags();     
        
        return true;
    }
    
    function delete_non_used_tags(){
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_tags_data.fieldset_tags_data_id');        
        $CI->db->from('fieldset_tags_data');
        $CI->db->join("fieldset_tags_mapping","fieldset_tags_mapping.fieldset_tags_data_id = fieldset_tags_data.fieldset_tags_data_id","LEFT");        
        $CI->db->where('fieldset_tags_mapping.fieldset_tags_data_id', null);
        $CI->db->where('fieldset_tags_mapping.fieldset_tags_id', null);        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_tags_data){
                $CI->db->where('fieldset_tags_data.fieldset_tags_data_id', $fieldset_tags_data->fieldset_tags_data_id);        
                $CI->db->delete("fieldset_tags_data");
            }
        }                
    }
    
    function index($fieldset_id) {
        
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select("fieldset_tags_data.fieldset_tags_data_id, tag_name, key");
        $CI->db->from('fieldset_tags_mapping');
        $CI->db->join('fieldset_tags_data', 'fieldset_tags_data.fieldset_tags_data_id = fieldset_tags_mapping.fieldset_tags_data_id');
        $CI->db->where('fieldset_tags_id', $fieldset_id);
        
        $query = $CI->db->get();
        
        $id_index = "|*|";
        $name_index = "|*|";
        $key_index = "|*|";
        
        foreach ($query->result() as $row) {
            $id_index .= $row->fieldset_tags_data_id . "|*|";
            $name_index .= $row->tag_name . "|*|";
            $key_index .= $row->key . "|*|";
        }
        
        $values = array();
        $fieldset_config = $this->get_fieldset_config_row($fieldset_id);
        $values["fieldset_key"] = $fieldset_config->key;
        $values["id_index"] = $id_index;
        $values["name_index"] = $name_index;
        $values["key_index"] = $key_index;
        $CI->db->where("fieldset_tags_id", $fieldset_id);
        $CI->db->update("fieldset_tags", $values);
        
    }
    
    // This is used whenever a category is updated / deleted to refresh the index
    function index_for_a_tag($fieldset_tags_data_id) {
        
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select("fieldset_tags_id");
        $CI->db->from('fieldset_tags_mapping');
        $CI->db->where('fieldset_tags_data_id', $fieldset_tags_data_id);
        
        $query = $CI->db->get();
        
        foreach ($query->result() as $row) {
            $this->index($row->fieldset_tags_id);
        }
        
    }
 
    function get_tag_group_keys($fieldset_id){
        $group_keys = array();
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_config.parameters');
        $CI->db->from('fieldset_tags');
        $this->db->join('fieldset_config', 'fieldset_tags.fieldset_config_id = fieldset_config.fieldset_config_id');
        $CI->db->where('fieldset_tags.fieldset_tags_id', $fieldset_id);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            parse_str($query->row('parameters'), $parameters);
            $group_keys = $parameters["group"];
        }
        return $group_keys;
    }
    
    function get_tags_by_query($group_key, $term, $existing_tags_id, $new_tags){
        $tags = array();
        $CI =& get_instance();
        $CI->load->database();

        $term_matches_new_tags = false;
        $new_tags_array = explode("||", $new_tags);
        foreach($new_tags_array as $new_tag){
            if(strtolower($term) == strtolower($new_tag)){
                $term_matches_new_tags = true;
            }
        }
        if($term_matches_new_tags == false){
            $CI->db->select('fieldset_tags_data.fieldset_tags_data_id');
            $CI->db->from('fieldset_tags_data');
            $CI->db->where('fieldset_tags_data.group_key', $group_key);
            $CI->db->where("fieldset_tags_data.tag_name", $term);        
            $query = $CI->db->get(); 
            if ($query->num_rows() == 0) {
                $tags[] = array(
                            "id" => -1,
                            "name" => $term,
                            "group_key" => $group_key
                );        
            }
        }

        $CI->db->select('fieldset_tags_data.fieldset_tags_data_id, fieldset_tags_data.tag_name');
        $CI->db->from('fieldset_tags_data');
        $CI->db->where('fieldset_tags_data.group_key', $group_key);
        $CI->db->where("fieldset_tags_data.tag_name LIKE '%$term%'");
        $CI->db->where_not_in("fieldset_tags_data.fieldset_tags_data_id", explode(",", $existing_tags_id));
        $query = $CI->db->get();        
        
        if ($query->num_rows() > 0) {
            foreach($query->result() as $row){
                $tags[] = array(
                    "id" => $row->fieldset_tags_data_id,
                    "name" => $row->tag_name,
                    "group_key" => $group_key);
            }
        }        
        return $tags;
        
    }
    
    private function generate_new_tag_key($key){
        $CI =& get_instance();
        
        $key = str_replace(' ', '_', strtolower(trim($key)));
        $pre_key = $key;
        $key_found = true;
        $suffix = 0;
        while($key_found == true){
            $CI->db->select('fieldset_tags_data.key');        
            $CI->db->from('fieldset_tags_data');
            $CI->db->where('fieldset_tags_data.key', $key);
            $query = $CI->db->get();
            if ($query->num_rows() == 0) {
                $key_found = false;
            }else{
                $suffix ++;
                $key = $pre_key."_".$suffix;
            }
        }
        
        return $key;
    }
    
    function validate_tag_data($form) {
        $validation = array();
    
        if(get_if_set($form,"tag_name")==""){
            $validation["fields"]["tag_name"]["message"]["type"] = "error";
            $validation["fields"]["tag_name"]["message"]["text"] = "Tag name is required.";                    
        }             

        if(get_if_set($form,"key")==""){
            $validation["fields"]["key"]["message"]["type"] = "error";
            $validation["fields"]["key"]["message"]["text"] = "Key is required.";                    
        }else{
            $CI = & get_instance();
            $CI->load->database(); 
            $CI->db->select('fieldset_tags_data.key');        
            $CI->db->from('fieldset_tags_data');
            $CI->db->where('fieldset_tags_data.key', get_if_set($form,"key"));
            $CI->db->where('fieldset_tags_data.fieldset_tags_data_id !=', get_if_set($form,"fieldset_tags_data_id"));
            $query = $CI->db->get();
            if ($query->num_rows() != 0) {
                $validation["fields"]["key"]["message"]["type"] = "error";
                $validation["fields"]["key"]["message"]["text"] = "Key already exists.";                                    
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

    function tag_array_to_db($id, $form){
        if(isset($form)){
            $CI = & get_instance();
            $CI->load->database();        
            if($id){
                $CI->db->where('fieldset_tags_data_id', $id);
                $CI->db->update('fieldset_tags_data', $form);              
            }else{
                $CI->db->insert('fieldset_tags_data', $form);              
            }
        }
    } 
    
    function tag_db_to_array($id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
    
        //GET VERSION ID FIRST, ITS NEEDED
        $CI->db->select('*');
        $CI->db->from('fieldset_tags_data');
        $CI->db->where('fieldset_tags_data.fieldset_tags_data_id', $id);
        
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $form["fieldset_tags_data_id"] = $id;
            $form["key"] = get_object_property($row, "key");
            $form["tag_name"] = get_object_property($row, "tag_name");                
            
        }
        return $form;
    }     
    
}