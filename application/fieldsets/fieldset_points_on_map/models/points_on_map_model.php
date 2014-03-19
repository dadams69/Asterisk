<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class points_on_map_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "points_on_map";
        $this->auxillary_tables = array("fieldset_points_on_map_data");
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
        
        // Manage Name and Description
        
        $row = array();
        $row["name"] = get_if_set($form, "name", null);
        $row["description"] = get_if_set($form, "description", null);
        
        $CI->db->where('fieldset_points_on_map_id', $fieldset_id);
        $CI->db->update('fieldset_points_on_map', $row);
        
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
        
        // Manage Points
        $CI->db->select('fieldset_points_on_map_data.fieldset_points_on_map_data_id');                    
        $CI->db->from('fieldset_points_on_map_data');
        $CI->db->where("fieldset_points_on_map_data.fieldset_points_on_map_id",$fieldset_id);
      
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_points_on_map_data){
                if(!isset($form["fieldset_points_on_map_data"]) || !isset($form["fieldset_points_on_map_data"][$fieldset_points_on_map_data->fieldset_points_on_map_data_id])){
                    $CI->db->where('fieldset_points_on_map_data.fieldset_points_on_map_data_id', $fieldset_points_on_map_data->fieldset_points_on_map_data_id);
                    $CI->db->where('fieldset_points_on_map_id',$fieldset_id);
                    $CI->db->delete('fieldset_points_on_map_data');                
                }
            }
        }
        
        if (isset($form["fieldset_points_on_map_data"]) && is_array($form["fieldset_points_on_map_data"])) {
            
            foreach($form["fieldset_points_on_map_data"] as $fieldset_points_on_map_data) {        
                    $CI->db->select('*');
                    $CI->db->from('fieldset_points_on_map_data');
                    $CI->db->where('fieldset_points_on_map_data_id',$fieldset_points_on_map_data["fieldset_points_on_map_data_id"]);
                    $CI->db->where('fieldset_points_on_map_id',$fieldset_id);
                    $query = $CI->db->get();
                    
                    $fieldset_points_on_map_data["longitude"] = get_if_set($fieldset_points_on_map_data, "longitude", null);
                    $fieldset_points_on_map_data["latitude"] = get_if_set($fieldset_points_on_map_data, "latitude", null);
                    $fieldset_points_on_map_data["name"] = get_if_set($fieldset_points_on_map_data, "name", null);
                    $fieldset_points_on_map_data["description"] = get_if_set($fieldset_points_on_map_data, "description", null);
                    
                    if ($query->num_rows()== 0) {
                        $CI->db->insert("fieldset_points_on_map_data", array("fieldset_points_on_map_id"=>$fieldset_id,"name"=>$fieldset_points_on_map_data["name"],"description"=>$fieldset_points_on_map_data["description"],"longitude"=>$fieldset_points_on_map_data["longitude"],"latitude"=>$fieldset_points_on_map_data["latitude"]));
                    }else{
                        $CI->db->where('fieldset_points_on_map_data_id',$fieldset_points_on_map_data["fieldset_points_on_map_data_id"]);
                        $CI->db->where('fieldset_points_on_map_id',$fieldset_id);
                        $CI->db->update("fieldset_points_on_map_data", array("name"=>$fieldset_points_on_map_data["name"],"description"=>$fieldset_points_on_map_data["description"],"longitude"=>$fieldset_points_on_map_data["longitude"],"latitude"=>$fieldset_points_on_map_data["latitude"]));
                    }
            }   
        }
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        
        $CI->db->select('name, description');
        $CI->db->from('fieldset_points_on_map');
        $CI->db->where('fieldset_points_on_map_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["name"] = get_object_property($fieldset, "name");
            $form["description"] = get_object_property($fieldset, "description");
        }
        
        $CI->db->select('fieldset_points_on_map_data.fieldset_points_on_map_data_id,fieldset_points_on_map_data.name,fieldset_points_on_map_data.description,fieldset_points_on_map_data.longitude,fieldset_points_on_map_data.latitude');
        $CI->db->from('fieldset_points_on_map_data as fieldset_points_on_map_data');
        $CI->db->where('fieldset_points_on_map_data.fieldset_points_on_map_id', $fieldset_id);
        $CI->db->order_by('fieldset_points_on_map_data.fieldset_points_on_map_data_id','ASC');
        
        $form["fieldset_points_on_map_id"] = $fieldset_id;
        $form["fieldset_points_on_map_data"] = array();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                $arr_elm["fieldset_points_on_map_data_id"] = get_object_property($result, "fieldset_points_on_map_data_id");
                $arr_elm["name"] = get_object_property($result, "name");
                $arr_elm["description"] =  get_object_property($result, "description");
                $arr_elm["longitude"] = get_object_property($result, "longitude");
                $arr_elm["latitude"] = get_object_property($result, "latitude");
                $form["fieldset_points_on_map_data"][$arr_elm["fieldset_points_on_map_data_id"]] = $arr_elm;
            }
        }
        return $form;                    
    }
    
}