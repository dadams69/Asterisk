<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fieldset_Publish_Date extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("publish_date_model", "model");
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
                $data["message"]["text"] = "Publish Date saved.";
                $work_area_data["form"] = $this->model->db_to_array($fieldset_id);            
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["form"] = $this->model->db_to_array($fieldset_id);
        }
        //var_dump($work_area_data["form"]);
        //die();
        $data["content"]["work_area"] = $this->load->view("form", $work_area_data, true);
        echo json_encode($data);
        
    }    
    
}