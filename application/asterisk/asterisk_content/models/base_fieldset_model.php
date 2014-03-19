<?php

abstract class base_fieldset_model extends CI_Model {

    public $fieldset_key = "ABSTRACT_PROPERTY_MUST_BE_SET";
    public $auxillary_tables = array();
    
    abstract function validate($form);
    abstract function array_to_db($fieldset_id,$form);
    abstract function db_to_array($fieldset_id);
    
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }
    
    function delete_all($version_id) {
        $f = $this->fieldset_key;
        $fieldset_id = "fieldset_".$f."_id";
        $this->db->select($fieldset_id);
        $this->db->from('fieldset_'.$f);
        $this->db->where("version_id", $version_id);
        $query = $this->db->get();
        foreach ($query->result() as $result) {
            $this->delete($result->$fieldset_id);
        }
    }
    
    function delete($fieldset_id) {
        $f = $this->fieldset_key;
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->where('fieldset_'.$this->fieldset_key.'_id', $fieldset_id);
        $CI->db->delete('fieldset_'.$this->fieldset_key);
          
        return true;
    }
    
    function add($version_id, $fieldset_config_id) {
        $f = $this->fieldset_key;
        $CI =& get_instance();
        $CI->load->database();
        
        $message = array();
        
        $CI->db->select('fieldset_'.$f.'_id, fieldset_config_id');
        $CI->db->from('fieldset_'.$f);
        $CI->db->where('version_id', $version_id);
        $CI->db->where('fieldset_config_id',  $fieldset_config_id);
        
        $query = $CI->db->get();
        
        if ($query->num_rows() > 0) {
            $message["type"] = "error";
            $message["text"] = "Fieldset Already Exists";
        } else {
            
            $fieldset = array();
            $fieldset["version_id"] = $version_id;
            $fieldset["fieldset_config_id"] = $fieldset_config_id;
            
            $CI->db->insert('fieldset_'.$f, $fieldset);
            
            $message["type"] = "notice";
            $message["text"] = "Fieldset Added";
        }
        return $message;
    }
    
    function copy_all($version_id, $new_version_id){
        $f = $this->fieldset_key;
        
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->from('fieldset_' . $f);
        $CI->db->where('version_id', $version_id);
        $query = $this->db->get();
        
        if ($query->num_rows() > 0){
            foreach ($query->result_array() as $id=>$row) {
                $this->copy($row['fieldset_' . $f . '_id'], $new_version_id);
            }
        }
        return true;
    }
    
    function copy($fieldset_id, $new_version_id, $force = false){
        $f = $this->fieldset_key;
        $fieldset_row = $this->get_fieldset_row($fieldset_id);
        if ($this->version_has_fieldset_with_same_fieldset_config($new_version_id, $fieldset_row->fieldset_config_id) && !force ) {
            return false;
        } else {
            $CI =& get_instance();
            $CI->load->database();
            $CI->db->from('fieldset_' . $f);
            $CI->db->where('fieldset_' . $f . '_id', $fieldset_id);
            $query = $this->db->get();

            if ($query->num_rows() > 0){
                $rows = $query->result_array();
                $row = $rows[0];
                unset($row['fieldset_' . $f . '_id']);
                $row["version_id"] = $new_version_id;  
                $CI->db->insert("fieldset_" . $f, $row);
                $new_fieldset_id = $CI->db->insert_id();
                
                foreach($this->auxillary_tables as $table) {
                    $CI->db->from($table);
                    $CI->db->where('fieldset_' . $f . '_id', $fieldset_id);
                    $query = $this->db->get();
                    if ($query->num_rows() > 0)
                    {
                        $rows = $query->result_array();
                        foreach($rows as $row){
                            if(isset($row[$table . '_id'])) {
                                unset($row[$table . '_id']);
                            }
                            $row['fieldset_' . $f . '_id'] = $new_fieldset_id;
                            $CI->db->insert($table, $row);
                        }
                    }
                } 
            }
            return true;
        }
    }

    function mark_version_as_modified($version_id) {
        $this->load->database();        
        $this->db->where('version_id', $version_id);
        $this->db->set('last_modified_at', 'NOW()', FALSE);
        $this->db->update('version');
        
        $this->load->database();        
        $this->db->where('content_id', $this->get_content_id($version_id));
        $this->db->set('last_modified_at', 'NOW()', FALSE);
        $this->db->update('content');
        
    }
    
    function get_version_id($fieldset_id) {
        $this->load->database();        
        $this->db->select('version_id');
        
        $this->db->from('fieldset_' . $this->fieldset_key);
        $this->db->where('fieldset_' .$this->fieldset_key .'_id', $fieldset_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->version_id;
        } else {
            return -1;
        }
    }
    
    function get_content_id($version_id) {
        $this->load->database();        
        $this->db->select('content_id');
        $this->db->from('version');
        $this->db->where('version_id', $version_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->content_id;
        } else {
            return -1;
        }
    }
    
    function get_content_id_by_version($fieldset_id){
        $this->load->database();        
        $this->db->select('content_id');
        $this->db->from('version');
        $this->db->join('fieldset_' . $this->fieldset_key,'fieldset_' . $this->fieldset_key . '.version_id = version.version_id','inner');
        $this->db->where('fieldset_' . $this->fieldset_key . '_id', $fieldset_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $row = $query->row();
            return $row->content_id;
        } else {
            return -1;
        }        
    }
    
    
    function get_fieldset_parameters_array($id, $mode = "fieldset_id"){
        $f = $this->fieldset_key;
        
        $CI =& get_instance();
        $CI->load->database();
        
        if ($mode == "fieldset_id") {
            $CI->db->select('fieldset_config.parameters');
            $CI->db->from('fieldset_'.$f);
            $this->db->join('fieldset_config', 'fieldset_'.$f.'.fieldset_config_id = fieldset_config.fieldset_config_id');
            $CI->db->where('fieldset_'.$f.'.fieldset_'.$f.'_id', $id);
        } else if ($mode == "fieldset_config_id") {
            $CI->db->select('fieldset_config.parameters');
            $CI->db->from('fieldset_config');
            $CI->db->where('fieldset_config.fieldset_config_id', $id);
        } else {
            die("Error in get_fieldset_parameters_array");
        }
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            $parameters = array();
            parse_str($query->row('parameters'), $parameters);
            return $parameters;
        } else {
            return array();
        }
    }
    
    
    
    
    
    
    function version_has_fieldset_with_same_fieldset_config($version_id, $fieldset_config_id) { 
        $f = $this->fieldset_key;
        $this->db->select("*");
        $this->db->from("fieldset_" . $f);
        $this->db->where("version_id", $version_id);
        $this->db->where("fieldset_config_id", $fieldset_config_id);
        $query = $this->db->get();
        return ($query->num_rows() > 0);
    }
    
    private function get_fieldset_row($fieldset_id) {
        $f = $this->fieldset_key;
        
        $this->db->select("*");
        $this->db->from("fieldset_" . $f);
        $this->db->where("fieldset_".$f."_id", $fieldset_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return;
        }
    }
    
    function get_fieldset_config_row($id, $mode = "fieldset_id"){
        $f = $this->fieldset_key;
        
        $CI =& get_instance();
        $CI->load->database();
        
        if ($mode == "fieldset_id") {
            $CI->db->select('fieldset_config.*');
            $CI->db->from('fieldset_'.$f);
            $this->db->join('fieldset_config', 'fieldset_'.$f.'.fieldset_config_id = fieldset_config.fieldset_config_id');
            $CI->db->where('fieldset_'.$f.'.fieldset_'.$f.'_id', $id);
        } else if ($mode == "fieldset_config_id") {
            $CI->db->select('fieldset_config.*');
            $CI->db->from('fieldset_config');
            $CI->db->where('fieldset_config.fieldset_config_id', $id);
        } else {
            die("Error in get_fieldset_parameters_array");
        }
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return;
        }
    }
    
    function get_fieldset_array($version_id) {
        $f = $this->fieldset_key;
        
        $CI =& get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_' . $f . '_id, fieldset_config.name as name, fieldset_config.fieldset_config_id, fieldset_config.rank');
        $CI->db->from('fieldset_' . $f . '');
        $this->db->join('fieldset_config', 'fieldset_' . $f . '.fieldset_config_id = fieldset_config.fieldset_config_id');
        $CI->db->where('version_id', $version_id);
        $CI->db->order_by('fieldset_config.rank asc');
        $query = $CI->db->get();
        $fieldset_array = array();
        
        foreach ($query->result() as $row)
        {
           $id_column_name = 'fieldset_' . $f . '_id';
           $fieldset_information = array();
           $fieldset_information["url"] = '/fieldset_' . $f . '/form/' . $row->$id_column_name;
           $fieldset_information["name"] = $row->name;
           $fieldset_information["is_allowed"] = false;
           $fieldset_information["fieldset_config_rank"] = $row->rank;
           $fieldset_array[$row->fieldset_config_id] = $fieldset_information;
        }
        
        return $fieldset_array;
    }
    
    
    function schema_exists() {
        $f = $this->fieldset_key;
        
        $has_schema = true;
        try {
           $query = "select 1 from fieldset_" . $f;
           $result = mysql_query($query);
           if(!$result){
               $has_schema = false;
           }
        } catch(Exception $e) {
            $has_schema = false;
        }

        return $has_schema;
    }
    
}