<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fieldset_Author extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("author_model", "model");
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
                $data["message"]["text"] = "Authors saved.";
                $work_area_data["form"] = $this->model->db_to_array($fieldset_id);            
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["form"] = $this->model->db_to_array($fieldset_id);
        }

        $data["content"]["work_area"] = $this->load->view("form", $work_area_data, true);
        echo json_encode($data);
        
    } 
    
    
    public function fieldset_author_data_list($display = "full"){
            $modal_data = array();
            $modal_data["authors"] = $this->model->get_fieldset_author_data_list();
            //$modal_data["version_id"] = $version_id;
            //$modal_data["form"] = array();
            //$modal_data["validation"] = array();
            $modal_data["display"] = $display;
            $data["content"]["modal"] = $this->load->view("_author_list", $modal_data, true); 
            echo json_encode($data);
    } 
    
    public function fieldset_author_data_form($id = null){
        $data = array();
        $form = $this->input->post("form");
        if ($form) {
            $work_area_data["validation"] = $this->model->validate_author_data($form);
            if ($work_area_data["validation"]["error"]) {
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Check your errors.";                
                $work_area_data["form"] = $form;
                $data["content"]["modal"] = $this->load->view("_author_form", $work_area_data, true);
            }else{
                $this->model->author_array_to_db($id,$form);            
                $data["message"]["type"] = "success";
                $data["message"]["text"] = "Authors saved.";
                $this->fieldset_author_data_list("partial");
                return;
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["form"] = $this->model->author_db_to_array($id);
            $data["content"]["modal"] = $this->load->view("_author_form", $work_area_data, true);
        }
        echo json_encode($data);  
    }
    
    public function delete_fieldset_author_data($fieldset_author_data_id) {
        $this->model->delete_fieldset_author_data($fieldset_author_data_id);
        $this->fieldset_author_data_list("partial");
    }
    
    public function find_author(){
        $term = $this->input->get("term");
        $authors = $this->model->get_author_by_query($term);
        echo json_encode($authors);
    }
    
    public function partial_author_list_modal($version_id = "", $fieldset_author_id_to_remove = null) {
        
        $form = $this->input->post("form");
        if(!$form){
         
                
                  
            
        }else{            
            $work_area_data["validation"] = $this->model->validate_author_data($form);
            if ($work_area_data["validation"]["error"]) {
                $work_area_data["message"]["type"] = "error";
                $work_area_data["message"]["text"] = "Check your errors.";                
                $work_area_data["authors"] = $this->model->get_author_list($form);
                $work_area_data["version_id"] = $version_id;
                $work_area_data["form"] = $form;            
                $work_area_data["mode"] = "edit";
                $work_area_data["display"] = "partial";                
                $data["content"]["modal_work_area"] = $this->load->view("_author_list_modal", $work_area_data, true);       
            }else{
                $this->model->author_data_array_to_db($form);            
                $work_area_data["message"]["type"] = "success";
                $work_area_data["message"]["text"] = "Authors saved.";
                $work_area_data["authors"] = $this->model->get_author_list($form);
                $work_area_data["version_id"] = $version_id;
                $work_area_data["form"] = array();
                $work_area_data["validation"] = array();                
                $work_area_data["mode"] = "list";
                $work_area_data["display"] = "partial";                                
                $data["content"]["modal_work_area"] = $this->load->view("_author_list_modal", $work_area_data, true);       
            }            
            
        }
        
            
        echo json_encode($data);
    }    
}