<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fieldset_Link extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("link_model", "model");
    }
    
    public function form($fieldset_id){
        
        $data = array();
        
        $form = $this->input->post("form");
        if ($form) {
            $work_area_data["validation"] = $this->model->validate($form);
            if ($work_area_data["validation"]["error"]) {
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Check your errors.";                
                $work_area_data["form"] = $form;            
            }else{                            
                $this->model->array_to_db($fieldset_id,$form);            
                $data["message"]["type"] = "success";
                $data["message"]["text"] = "Link saved.";
                $work_area_data["form"] = $this->model->db_to_array($fieldset_id);            
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["form"] = $this->model->db_to_array($fieldset_id);
        }
        $data["content"]["work_area"] = $this->load->view("form", $work_area_data, true);
        echo json_encode($data);
        
    }    
    
    public function get_images($version_id){
        
        $this->load->database();
        $this->db->select('fieldset_file_data.fieldset_file_data_id, fieldset_file_data.filename, fieldset_file_data.extension, fieldset_file_data.title, fieldset_file_data.size, fieldset_file_data.image_height, fieldset_file_data.image_width ');
        $this->db->from('fieldset_file_data as fieldset_file_data');
        $this->db->join('fieldset_file_mapping as fieldset_file_mapping', "fieldset_file_data.fieldset_file_data_id = fieldset_file_mapping.fieldset_file_data_id","join");
        $this->db->join('fieldset_file as fieldset_file', "fieldset_file.fieldset_file_id = fieldset_file_mapping.fieldset_file_id","join");
        $this->db->where('fieldset_file.version_id', $version_id);        
        $this->db->where_in('fieldset_file_data.extension', array("jpeg","bmp","jpg","gif","png"));        
       
        $images = array();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                
                if(get_object_property($result, "size") && is_numeric(get_object_property($result, "size"))){
                    if ($result->size >= 1000000000)
                        $file_size = round(($result->size / 1000000000),2).' GB';
                    if ($result->size >= 1000000) 
                        $file_size = round(($result->size / 1000000),2).' MB';
                    else
                        $file_size = round(($result->size / 1000),2).' KB';                    
                }else{
                    $file_size = "";
                }                
                
                $arr_elm = array();
                $arr_elm["thumb"] = base_url("public/files/".get_object_property($result, "fieldset_file_data_id")."/".get_object_property($result, "filename").".".get_object_property($result, "extension")); ;
                $arr_elm["image"] = base_url("public/files/".get_object_property($result, "fieldset_file_data_id")."/".get_object_property($result, "filename").".".get_object_property($result, "extension")); ;
                $arr_elm["title"] = get_object_property($result, "title")." (".$file_size." ".get_object_property($result, "image_width")."x".get_object_property($result, "image_height").")";
                //$arr_elm["folder"] = "In this version";               
                $images[] = $arr_elm;
            }
        }
        echo json_encode($images);                            
        
    }    
}