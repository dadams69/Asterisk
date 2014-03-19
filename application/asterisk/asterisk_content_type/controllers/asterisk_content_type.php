<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asterisk_Content_Type extends CI_Controller {

    public function __construct() {
        parent::__construct();
        //$this->load->helper('file');
    }

    public function index() {
        auth_require_login(true);
        $data = array();
        $data["content_types"] = $this->get_table_data();

        $this->load->view("index", $data);
    }
    
    private function get_table_data(){
        $this->load->model("content_type_model", "model");
        
        $content_types = $this->model->get_all_content_types();
        
        foreach($content_types as $key => $value) {
            $content_types[$key]["content_type_fieldset_configs"] = $this->model->get_content_type_fieldset_configs($content_types[$key]["content_type_id"]);
        }
        
        return $content_types;        
    }
    
    public function save_content_type_rank(){
        auth_require_login(true);
        $form = $this->input->post("form");
        $this->load->database();
        $content_type_to_update = array();
        if(isset($form["content_type"])){
            foreach($form["content_type"] as $content_type_id => $content_type){
                $content_type_to_update[] = array("content_type_id"=>$content_type_id,"rank"=>$content_type["rank"]);
            }
        }
        $this->db->update_batch("content_type",$content_type_to_update,"content_type_id");

        $data["message"]["type"] = "success";
        $data["message"]["text"] = "Changes saved.";
        $data["content"]["table_container"] = $this->load->view("_table",array("content_types"=>$this->get_table_data()),true);
        echo json_encode($data);                
    }
    
    public function save_content_type_fieldset_config_rank(){
        auth_require_login(true);
        $form = $this->input->post("form");
        $this->load->database();
        $content_type_fieldset_config_to_update = array();
        if(isset($form["content_type"])){
            foreach($form["content_type"] as $content_type){
                if(isset($content_type["content_type_fieldset_config"])){
                    foreach($content_type["content_type_fieldset_config"] as $content_type_fieldset_config_id => $content_type_fieldset_config){
                        $content_type_fieldset_config_to_update[] = array("content_type_fieldset_config_id"=>$content_type_fieldset_config_id,"rank"=>$content_type_fieldset_config["rank"]);
                    }
                }
            }
        }
        $this->db->update_batch("content_type_fieldset_config",$content_type_fieldset_config_to_update,"content_type_fieldset_config_id");
        
        
        $data["message"]["type"] = "success";
        $data["message"]["text"] = "Changes saved.";
        $data["content"]["table_container"] = $this->load->view("_table",array("content_types"=>$this->get_table_data()),true);
        echo json_encode($data);        
    }    
    
    public function content_type_form($content_type_id=null){
        auth_require_login(true);
        
        $this->load->model("content_type_model", "model");
        
        $data = array();
        $work_area_data = array();
        
        $form = $this->input->post("form");
        if ($form) {
            $work_area_data["validation"] = $this->model->validate($form);
            if ($work_area_data["validation"]["error"]) {
                $work_area_data["form"] = $form;            
                $data["content"]["content_type_form_modal"] = $this->load->view("_content_type_form",$work_area_data,true);            
            }else{                                                        
                $this->model->content_type_array_to_db($form);            
                $data["message"]["type"] = "success";
                if(get_if_set($form, "content_type_id")!=""){
                    $data["message"]["text"] = "Content Type saved.";
                }else{
                    $data["message"]["text"] = "Content Type Added.";
                }
                $data["content"]["table_container"] = $this->load->view("_table",array("content_types"=>$this->get_table_data()),true);            
            }
        } else {        
            if($content_type_id){
                $this->load->database();
                $work_area_data["form"] = $this->model->content_type_db_to_array($content_type_id); 
                if (count($work_area_data["form"]) > 0){
                    $work_area_data["validation"] = array();    
                    $data["content"]["content_type_form_modal"] = $this->load->view("_content_type_form",$work_area_data,true);            
                }else{
                    $data["message"]["type"] = "error";
                    $data["message"]["text"] = "Content Type not found.";                
                }            
            }else{
                $work_area_data["validation"] = array();    
                $work_area_data["form"] = array();
                $data["content"]["content_type_form_modal"] = $this->load->view("_content_type_form",$work_area_data,true);            
            }
        }
        echo json_encode($data);
    }
    
    public function fieldset_config_form($content_type_id=null){
        auth_require_login(true);
        
        $this->load->model("content_type_model", "model");
        
        $data = array();
        $work_area_data = array();
        
        $form = $this->input->post("form");
        if ($form) {
            $work_area_data["validation"] = $this->model->fieldset_config_validate($form);
            if ($work_area_data["validation"]["error"]) {
                $work_area_data["form"] = $form;            
                $data["content"]["content_type_form_modal"] = $this->load->view("_fieldset_config_form",$work_area_data,true);            
            }else{                                 
                if(get_if_set($form, "content_type_id") != ""){
                    if(get_if_set($form,"add_fieldset_config",null) && count($form["add_fieldset_config"])>0){
                        $this->model->content_type_fieldset_config_array_to_db($form);            
                        $data["message"]["type"] = "success";
                        $data["message"]["text"] = "Config(s) Added.";
                    }else{
                        $data["message"]["type"] = "warning";
                        $data["message"]["text"] = "No config has been added.";                                            
                    }
                }else{
                    $data["message"]["type"] = "error";
                    $data["message"]["text"] = "Missing parameters.";                    
                }
                $data["content"]["table_container"] = $this->load->view("_table",array("content_types"=>$this->get_table_data()),true);            
            }
        } else {        
            if($content_type_id){
                $this->load->database();
                $work_area_data["validation"] = array();
                $work_area_data["form"] = $this->model->fieldset_config_db_to_array($content_type_id); 
                $work_area_data["form"]["content_type_id"] = $content_type_id;
                $data["content"]["content_type_form_modal"] = $this->load->view("_fieldset_config_form",$work_area_data,true);            
            }else{
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Invalid parameters.";
            }
        }
        echo json_encode($data);
    }
    
    public function delete($content_type_id = null){
        auth_require_login(true);
        
        $data = array();
        if($content_type_id){
            $this->load->database();
            $this->db->delete('content_type', array('content_type_id' => $content_type_id));
            $data["content"]["table_container"] = $this->load->view("_table",array("content_types"=>$this->get_table_data()),true);            
            $data["message"]["type"] = "warning";
            $data["message"]["text"] = "Content Type deleted.";                        
        }else{
            $data["message"]["type"] = "error";
            $data["message"]["text"] = "Invalid parameters.";            
        }
        echo json_encode($data);
    }    
    
    public function delete_config($content_type_fieldset_config_id = null){
        auth_require_login(true);
        
        $data = array();
        if($content_type_fieldset_config_id){
            $this->load->database();
            $this->db->delete('content_type_fieldset_config', array('content_type_fieldset_config_id' => $content_type_fieldset_config_id));
            $data["content"]["table_container"] = $this->load->view("_table",array("content_types"=>$this->get_table_data()),true);            
            $data["message"]["type"] = "warning";
            $data["message"]["text"] = "Content Type config deleted.";                        
        }else{
            $data["message"]["type"] = "error";
            $data["message"]["text"] = "Invalid parameters.";            
        }
        echo json_encode($data);
    }
}
