<?php

function find_all($table){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select("*");
    $CI->db->from($table);
    $query = $CI->db->get();
    $results = array();
    if ($query->num_rows() > 0){
        $results = $query->result_array();    
    }
    return $results;
}

function find_all_by($table, $fields = array()){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select("*");
    $CI->db->from($table);
    foreach($fields as $field_name => $field_value){
        $CI->db->where($field_name,$field_value);
    }
    $query = $CI->db->get();
    $results = array();
    if ($query->num_rows() > 0){
        $results = $query->result_array();    
    }
    return $results;    
}

function find_one_by_id($table,$id){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select("*");
    $CI->db->from($table);
    $CI->db->where($table."_id",$id);
    $query = $CI->db->get();
    if ($query->num_rows() > 0){
        $result = $query->result_array();    
        return $result[0];
    }else{
        return null;
    }        
}

function find_one_by($table, $fields = array()){
    $CI =& get_instance();
    $CI->load->database();
    $CI->db->select("*");
    $CI->db->from($table);
    foreach($fields as $field_name => $field_value){
        $CI->db->where($field_name,$field_value);
    }
    $query = $CI->db->get();
    if ($query->num_rows() > 0){
        $result = $query->result_array();    
        return $result[0];
    }else{
        return null;
    }    
}
