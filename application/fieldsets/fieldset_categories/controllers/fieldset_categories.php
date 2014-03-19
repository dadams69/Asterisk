<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fieldset_Categories extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("categories_model", "model");
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
                $data["message"]["text"] = "Categories saved.";
                $work_area_data["form"] = $this->model->db_to_array($fieldset_id);            
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["form"] = $this->model->db_to_array($fieldset_id);
        }

        $this->model->index($fieldset_id);
        $group =  $this->model->get_categories_group_key($fieldset_id);
        $work_area_data["group"] = $group;
        $work_area_data["categories"] = $this->model->get_categories($group["key"]);        
        $data["content"]["work_area"] = $this->load->view("form", $work_area_data, true);
        echo json_encode($data);
        
    }    
    
    public function categories_form($fieldset_id){
        
        $data = array();
        
        $work_area_data["fieldset_categories_id"] = $fieldset_id;
        $group =  $this->model->get_categories_group_key($fieldset_id);
        $work_area_data["group"] = $group;
        
        $form = $this->input->post("form");
        if ($form) {
            $work_area_data["validation"] = $this->model->categories_validate($form);
            if ($work_area_data["validation"]["error"]) {
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Check your errors.";                
                $work_area_data["form"] = $form;            
            }else{                            
                $this->model->categories_array_to_db($group,$form);            
                $data["message"]["type"] = "success";
                $data["message"]["text"] = "Categories saved.";
                $work_area_data["form"] = $this->model->get_categories($group["key"]);        
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["form"] = $this->model->get_categories($group["key"]);        
        }
        
        $data["content"]["work_area"] = $this->load->view("_categories_form", $work_area_data, true);
        echo json_encode($data);
        
    }        
}