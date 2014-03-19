<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fieldset_Tags extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("tags_model", "model");
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
                $data["message"]["text"] = "Tags saved.";
                $work_area_data["form"] = $this->model->db_to_array($fieldset_id);            
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["form"] = $this->model->db_to_array($fieldset_id);
        }

        $work_area_data["group_keys"] = $this->model->get_tag_group_keys($fieldset_id);        
        $data["content"]["work_area"] = $this->load->view("form", $work_area_data, true);
        echo json_encode($data);
        
    }    
    
    public function find_tags($group_key){
        
        $term = $this->input->get("term");
        $existing_tags_id = $this->input->get("existing_tags_id");
        $new_tags = $this->input->get("new_tags");
        $tags = $this->model->get_tags_by_query($group_key, $term, $existing_tags_id, $new_tags);
        echo json_encode($tags);
    }
    
    public function fieldset_tags_data_form($id = null){
        $data = array();
        $form = $this->input->post("form");
        if ($form) {
            $form["key"] = str_replace(' ', '_', strtolower(trim($form["key"])));
            $work_area_data["validation"] = $this->model->validate_tag_data($form);
            if ($work_area_data["validation"]["error"]) {
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Check your errors.";                
                $work_area_data["full_view"] = false;
                $work_area_data["form"] = $form;
                $data["content"]["modal"] = $this->load->view("_tag_form", $work_area_data, true);
            }else{
                $this->model->tag_array_to_db($id,$form);            
                $data["message"]["type"] = "success";
                $data["message"]["text"] = "Tag saved.";
                $data["content"]["modal"] = "";
                $data["tag_name"] = $form["tag_name"];
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["full_view"] = true;
            $work_area_data["form"] = $this->model->tag_db_to_array($id);
            $data["content"]["modal"] = $this->load->view("_tag_form", $work_area_data, true);
        }
        echo json_encode($data);  
    }    
}