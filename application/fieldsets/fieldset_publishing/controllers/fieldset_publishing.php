<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fieldset_Publishing extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("publishing_model", "model");
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
                $data["message"]["text"] = "Publishing information saved.";
                $work_area_data["form"] = $this->model->db_to_array($fieldset_id);            
            }
        } else {
            $work_area_data["validation"] = array();
            $work_area_data["form"] = $this->model->db_to_array($fieldset_id);
        }
        $data["content"]["work_area"] = $this->load->view("form", $work_area_data, true);
        echo json_encode($data);
        
    }  
    
    public function get_packages(){
        $term = $this->input->get("term");
        
        $this->load->database();
        $this->db->select('fieldset_publishing.package');
        $this->db->from('fieldset_publishing');
        $this->db->group_by("fieldset_publishing.package");
        $this->db->where("fieldset_publishing.package LIKE '%$term%'");
        
        
        $query = $this->db->get();        
        $packages = array();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $row){
                $packages[] = array(
                    "id" => $row->package,
                    "label" => $row->package);
            }
        }        
        
        echo json_encode($packages);
    }
}