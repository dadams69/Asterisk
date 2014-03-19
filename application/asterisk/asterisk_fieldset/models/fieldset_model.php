<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

class fieldset_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function validate($form){
        $validation = array();
        
        $status = get_if_set($form, "status");
        if ($status == "") {
            $validation["fields"]["status"]["message"]["type"] = "error";
            $validation["fields"]["status"]["message"]["text"] = "Status is required.";
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
    
    function config_validate($form){
        $validation = array();
        
        $key = get_if_set($form, "key");
        if ($key == "") {
            $validation["fields"]["key"]["message"]["type"] = "error";
            $validation["fields"]["key"]["message"]["text"] = "Key is required.";
        }              

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

    function get_all_fieldsets(){
        
            $fieldsets = array();
            
            $this->load->database();
            $this->db->select("*");
            $this->db->from('fieldset');
            $this->db->order_by("rank");
            
            $query = $this->db->get();
            
            // Place all fieldsets from DB into array
            foreach($query->result() as $result) {
                $fieldset = array();
                $fieldset["fieldset_id"] = $result->fieldset_id;
                // $fieldset["name"] = $result->name; DELETE THIS COLUMN
                $fieldset["key"] = $result->key;
                $fieldset["rank"] = $result->rank;
                $fieldset["installed"] = true;
                $fieldset["files_exist"] = false;
                $fieldset["schema_exists"] = false;
                $fieldsets[$result->key] = $fieldset;
            }
            
            // Loop through folders, setting files_exist to true if found, or adding new fieldsets
            
            $path = './application/fieldsets'; // '.' for current
            foreach (new DirectoryIterator($path) as $file)
            {
                if($file->isDot()) continue;
                if($file->isDir())
                {
                    $found = false;
                    $folder_name = $file->getFilename();
                    if (substr($folder_name, 0, 9) == "fieldset_") {
                        $fieldset_key = substr($folder_name, 9);
                        if (isset($fieldsets[$fieldset_key])) {
                            $fieldsets[$fieldset_key]["files_exist"] = true;
                            $found = true;
                        }
                        if (!$found) {
                            $fieldset = array();
                            $fieldset["fieldset_id"] = null;
                            $fieldset["key"] = $fieldset_key;
                            $fieldset["rank"] = null;
                            $fieldset["installed"] = false;
                            $fieldset["files_exist"] = true;
                            $fieldset["schema_exists"] = false;
                            $fieldsets[$fieldset_key] = $fieldset; 
                        }
                    }
                }
            }
            
            // For fieldsets with files, see if schema exists
            foreach($fieldsets as $key => $value) {
                if ($fieldsets[$key]["files_exist"] == true) {
                    $this->load->model('fieldset_' . $fieldsets[$key]["key"] . '/' . $fieldsets[$key]["key"] . '_model');
                    $f = $fieldsets[$key]["key"] . '_model';
                    $fieldsets[$key]["schema_exists"] = $this->$f->schema_exists();
                }
            }
            
            return $fieldsets;
    }
    
     function get_fieldset_configs($fieldset_id){
        
            $fieldsets = array();
            
            $this->load->database();
            $this->db->select("*");
            $this->db->from('fieldset_config');
            $this->db->where('fieldset_id', $fieldset_id);
            $this->db->order_by("rank");
            
            $query = $this->db->get()->result_array();
            
            return $query;
    }
    
    function config_array_to_db($form){
        $CI = & get_instance();
        $CI->load->database();       
        $form["key"] = str_replace(' ', '_', strtolower(trim($form["key"])));
        if(get_if_set($form,"fieldset_config_id")!=""){
            $CI->db->where(array("fieldset_config_id"=>$form["fieldset_config_id"],"fieldset_id"=>$form["fieldset_id"]));
            $CI->db->update('fieldset_config', $form);  
        }else{
            
            $rank = 0;
            $this->db->select_max('rank');
            $CI->db->where('fieldset_id', $form["fieldset_id"]);
            $query = $this->db->get('fieldset_config');
            $result = $query->result();
            if($result && count($result) > 0){
                $rank = $result[0]->rank;
            }
            $rank++;            
            $form["rank"] = $rank;
            unset($form["fieldset_config_id"]);
            $this->db->insert('fieldset_config', $form);
        }
    }
    
    function fieldset_db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset');
        $CI->db->where('fieldset_id', $fieldset_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset = $query->row();
            $form["fieldset_id"] = get_object_property($fieldset, "fieldset_id");
            //$form["key"] = get_object_property($fieldset, "key");
            //$form["rank"] = get_object_property($fieldset, "rank");
        }
        return $form;                    
    }

    function fieldset_config_db_to_array($fieldset_config_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('*');
        $CI->db->from('fieldset_config');
        $CI->db->where('fieldset_config_id', $fieldset_config_id);
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $fieldset_config = $query->row();
            $form["fieldset_config_id"] = get_object_property($fieldset_config, "fieldset_config_id");
            $form["fieldset_id"] = get_object_property($fieldset_config, "fieldset_id");
            $form["key"] = get_object_property($fieldset_config, "key");
            $form["name"] = get_object_property($fieldset_config, "name");
            $form["parameters"] = get_object_property($fieldset_config, "parameters");
            //$form["rank"] = get_object_property($fieldset_config, "rank");
        }
        return $form;                    
    }    
    
}