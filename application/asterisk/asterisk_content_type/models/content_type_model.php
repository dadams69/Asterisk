<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

class content_type_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function validate($form){
        $validation = array();
        
        $name = get_if_set($form, "name");
        if ($name == "") {
            $validation["fields"]["name"]["message"]["type"] = "error";
            $validation["fields"]["name"]["message"]["text"] = "Name is required.";
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
    
    function fieldset_config_validate($form){
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

    function get_all_content_types(){
        
            $content_types = array();
            
            $this->load->database();
            $this->db->select("*");
            $this->db->from('content_type');
            $this->db->order_by("rank");
            
            $query = $this->db->get();
            
            // Place all content_types from DB into array
            foreach($query->result() as $result) {
                $content_type = array();
                $content_type["content_type_id"] = $result->content_type_id;
                $content_type["name"] = $result->name;
                $content_type["rank"] = $result->rank;                
                $content_types[$result->content_type_id] = $content_type;
            }
            
            return $content_types;
    }
    
    function get_content_type_fieldset_configs($content_type_id){
        
            $content_types = array();
            
            $this->load->database();
            $this->db->select("
                content_type_fieldset_config.content_type_fieldset_config_id, 
                content_type_fieldset_config.fieldset_config_id, 
                content_type_fieldset_config.rank, 
                fieldset_config.name, 
                fieldset.name as fieldset_name, 
                ");
            $this->db->from('content_type_fieldset_config');
            $this->db->join('fieldset_config','fieldset_config.fieldset_config_id = content_type_fieldset_config.fieldset_config_id','LEFT');
            $this->db->join('fieldset','fieldset.fieldset_id = fieldset_config.fieldset_id','LEFT');
            $this->db->where('content_type_fieldset_config.content_type_id', $content_type_id);
            $this->db->order_by("content_type_fieldset_config.rank");
            
            $query = $this->db->get()->result_array();
            
            return $query;
    }
    
    function content_type_db_to_array($content_type_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('content_type');
        $CI->db->where('content_type_id', $content_type_id);
            
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            $content_type = $query->row();
            $form["content_type_id"] = get_object_property($content_type, "content_type_id");
            $form["name"] = get_object_property($content_type, "name");
        }
        return $form;                    
    }
    
    function content_type_array_to_db($form){
        $CI = & get_instance();
        $CI->load->database();       
        
        if(get_if_set($form,"content_type_id")!=""){
            $CI->db->where(array("content_type_id"=>$form["content_type_id"]));
            $CI->db->update('content_type', $form);  
        }else{
            
            $rank = 0;
            $CI->db->select_max('rank');
            $query = $CI->db->get('content_type');
            $result = $query->result();
            if($result && count($result) > 0){
                $rank = $result[0]->rank;
            }
            $rank++;            
            $form["rank"] = $rank;
            unset($form["content_type_id"]);
            $CI->db->insert('content_type', $form);
        }
    }    

    function fieldset_config_db_to_array($content_type_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('
                fieldset_config.fieldset_config_id, 
                fieldset_config.name, 
                fieldset.name as fieldset_name
        ');
        $CI->db->from('fieldset_config');
        $CI->db->join('fieldset','fieldset.fieldset_id = fieldset_config.fieldset_id','INNER');
        $CI->db->join('content_type_fieldset_config','content_type_fieldset_config.fieldset_config_id = fieldset_config.fieldset_config_id AND content_type_fieldset_config.content_type_id = '.$content_type_id.'','LEFT');
        $this->db->where(array('content_type_fieldset_config.content_type_id' => NULL));
        
        $this->db->order_by('fieldset.rank,fieldset_config.rank');
            
        $query = $CI->db->get();
        // Place all content_types from DB into array
        foreach($query->result() as $result) {
            $fieldset_config = array();
            $fieldset_config["fieldset_config_id"] = $result->fieldset_config_id;
            $fieldset_config["name"] = $result->name;
            $fieldset_config["fieldset_name"] = $result->fieldset_name;                
            $fieldset_configs[$result->fieldset_config_id] = $fieldset_config;
        }
        $form["fieldset_configs"] = $fieldset_configs;
        return $form;                    
    }
    
    function content_type_fieldset_config_array_to_db($form){

        $CI = & get_instance();
        $CI->load->database();       
        $content_type_id = $form["content_type_id"];
        
        $rank = 0;
        $CI->db->select_max('rank');
        $CI->db->where('content_type_id', $form["content_type_id"]);
        $query = $CI->db->get('content_type_fieldset_config');
        $result = $query->result();
        if($result && count($result) > 0){
            $rank = $result[0]->rank;
        }
        
        foreach($form["add_fieldset_config"] as $fieldset_config_id){
            
            //check if its already added
            $this->db->select('content_type_fieldset_config.content_type_fieldset_config_id');
            $this->db->from('content_type_fieldset_config');
            $this->db->where('content_type_fieldset_config.content_type_id',$content_type_id);
            $this->db->where('content_type_fieldset_config.fieldset_config_id',$fieldset_config_id);
            
            if(count($this->db->get()->result()) == 0){
                //Add content type fieldset config
                $content_type_fieldset_config = array();
                $content_type_fieldset_config["content_type_id"] = $content_type_id;
                $content_type_fieldset_config["fieldset_config_id"] = $fieldset_config_id;
                $rank++;                                
                $content_type_fieldset_config["rank"] = $rank;
                $CI->db->insert('content_type_fieldset_config', $content_type_fieldset_config);
            }
        }
        
    }    
    
}