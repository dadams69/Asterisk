<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fieldset_S3_File extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("s3_file_model", "model");
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
                $data["message"]["text"] = "S3 File saved.";
                $work_area_data["form"] = $this->model->db_to_array($fieldset_id);            
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["form"] = $this->model->db_to_array($fieldset_id);
        }
        $data["content"]["work_area"] = $this->load->view("form", $work_area_data, true);
        echo json_encode($data);
        
    }    
    
   
    
    public function get_files($fieldset_id) {
        $this->load->library("s3");
        $parameters = $this->model->get_parameters($fieldset_id);
        $files = array();
        //Load Library
        
        if(isset($parameters["config"]) && isset($parameters["bucket"])){   
            $s3 = new S3($parameters["config"]); 
            
            
            if (($contents = $this->s3->getBucket($parameters["bucket"])) !== false) {
                foreach ($contents as $object) {
                    $files[] = array("filename"=>$object["name"]);
                }
            }         
            
        }
        echo json_encode($files);
    }        
}