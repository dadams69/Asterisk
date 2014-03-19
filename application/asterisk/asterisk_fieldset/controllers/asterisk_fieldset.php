<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asterisk_Fieldset extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
    }

    public function index() {
        auth_require_login(true);
        
        $data = array();
        $data["fieldsets"] = $this->get_table_data();
        $this->load->view("index", $data);
    }
    
    private function get_table_data(){
        $this->load->model("fieldset_model", "model");
        
        $fieldsets = $this->model->get_all_fieldsets();
        
        foreach($fieldsets as $key => $value) {
            $fieldsets[$key]["fieldset_configs"] = $this->model->get_fieldset_configs($fieldsets[$key]["fieldset_id"]);
        }
        
        return $fieldsets;        
    }
    
    public function save_fieldset_rank(){
        auth_require_login(true);
        $form = $this->input->post("form");
        $this->load->database();
        $fieldset_to_update = array();
        if(isset($form["fieldset"])){
            foreach($form["fieldset"] as $fieldset_id => $fieldset){
                $fieldset_to_update[] = array("fieldset_id"=>$fieldset_id,"rank"=>$fieldset["rank"]);
            }
        }
        $this->db->update_batch("fieldset",$fieldset_to_update,"fieldset_id");

        $data["message"]["type"] = "success";
        $data["message"]["text"] = "Changes saved.";
        $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);
        echo json_encode($data);                
    }
    
    public function save_fieldset_config_rank(){
        auth_require_login(true);
        $form = $this->input->post("form");
        $this->load->database();
        $fieldset_config_to_update = array();
        if(isset($form["fieldset"])){
            foreach($form["fieldset"] as $fieldset){
                if(isset($fieldset["fieldset_config"])){
                    foreach($fieldset["fieldset_config"] as $fieldset_config_id => $fieldset_config){
                        $fieldset_config_to_update[] = array("fieldset_config_id"=>$fieldset_config_id,"rank"=>$fieldset_config["rank"]);
                    }
                }
            }
        }
        $this->db->update_batch("fieldset_config",$fieldset_config_to_update,"fieldset_config_id");
        
        
        $data["message"]["type"] = "success";
        $data["message"]["text"] = "Changes saved.";
        $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);
        echo json_encode($data);        
    }    

    public function install_fieldset($fieldset_key = null){
        auth_require_login(true);
        
        $data = array();
        if($fieldset_key){
                    
            $this->load->database();

            $rank = 0;
            $this->db->select_max('rank');
            $query = $this->db->get('fieldset');
            $result = $query->result();
            if($result && count($result) > 0){
                $rank = $result[0]->rank;
            }

            $rank++;

            $this->db->insert('fieldset', array("key"=>$fieldset_key, "name"=> ucwords(str_replace("_", " ", $fieldset_key)), "rank"=> $rank));        

            $data["message"]["type"] = "success";
            $data["message"]["text"] = "Fieldset added.";
            $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);
        }else{
            $data["message"]["type"] = "error";
            $data["message"]["text"] = "Invalid parameters.";            
        }
        echo json_encode($data);
    }    
    
    public function uninstall_fieldset($fieldset_key = null){
        auth_require_login(true);
        
        $data = array();
        if($fieldset_key){
            $this->load->database();
            try{
                $this->db->delete('fieldset',array("key"=>$fieldset_key));
                $data["message"]["type"] = "warning";
                $data["message"]["text"] = "Fieldset uninstalled.";            
            }catch(Exception $e){
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Error on fieldset uninstall.";            
            }
            $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);
        }else{
            $data["message"]["type"] = "error";
            $data["message"]["text"] = "Invalid parameters.";            
        }
        echo json_encode($data);        
    }
    
    public function create_schema($fieldset_key = null){
        auth_require_login(true);
        
        $data = array();
        if($fieldset_key){
            $sql = file_get_contents("application/fieldsets/fieldset_".$fieldset_key."/create.sql");
            if($sql){
                $this->load->database();
                $this->db->trans_begin();
                
                foreach (explode(";", $sql) as $sql) {
                    $sql = trim($sql);
                    if ($sql) {
                        $this->db->query($sql);
                    }
                }
                
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $data["message"]["type"] = "warning";
                    $data["message"]["text"] = "Please check database.";                                                            
                }else{
                    $this->db->trans_commit();
                    $data["message"]["type"] = "success";
                    $data["message"]["text"] = "Fieldset added.";                                        
                }                
                $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);
            }else{
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Error on fieldset added.";
                $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);            
            }
        }else{
            $data["message"]["type"] = "error";
            $data["message"]["text"] = "Invalid parameters.";            
        }
        echo json_encode($data);        
    }    
    
    public function delete_schema($fieldset_key = null){
        auth_require_login(true);
        
        $data = array();
        if($fieldset_key){
            

            $sql = file_get_contents("application/fieldsets/fieldset_".$fieldset_key."/delete.sql");
            if($sql){
                $this->load->database();
                $this->db->trans_begin();
                
                foreach (explode(";", $sql) as $sql) {
                    $sql = trim($sql);
                    if ($sql) {
                        $this->db->query($sql);
                    }
                }
                
                if ($this->db->trans_status() === FALSE){
                    $this->db->trans_rollback();
                    $data["message"]["type"] = "warning";
                    $data["message"]["text"] = "Please check database.";                                                            
                }else{
                    $this->db->trans_commit();
                    $data["message"]["type"] = "success";
                    $data["message"]["text"] = "Fieldset removed.";                                        
                }                
                
                $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);
            }else{
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Error on fieldset remove.";
                $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);            
            }
        }else{
            $data["message"]["type"] = "error";
            $data["message"]["text"] = "Invalid parameters.";            
        }
        echo json_encode($data);        
    }    
    
    public function config_form($fieldset_id=null, $fieldset_config_id=null){
        auth_require_login(true);
        
        $this->load->model("fieldset_model", "model");
        
        $data = array();
        $work_area_data = array();
        
        $form = $this->input->post("form");
        if ($form) {
            $work_area_data["validation"] = $this->model->config_validate($form);
            if ($work_area_data["validation"]["error"]) {
                $work_area_data["form"] = $form;            
                $data["content"]["config_form_modal"] = $this->load->view("_config_form",$work_area_data,true);            
            }else{                                                        
                $this->model->config_array_to_db($form);            
                $data["message"]["type"] = "success";
                if(get_if_set($form, "fieldset_config_id")!=""){
                    $data["message"]["text"] = "Config saved.";
                }else{
                    $data["message"]["text"] = "Config Added.";
                }
                $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);            
            }
        } else {        
            if($fieldset_id){
                $this->load->database();
                $work_area_data["form"] = $this->model->fieldset_db_to_array($fieldset_id); 
                if (count($work_area_data["form"]) > 0){
                    $work_area_data["validation"] = array();
                    if($fieldset_config_id){    
                        $work_area_data["form"] = $this->model->fieldset_config_db_to_array($fieldset_config_id); 
                        if (count($work_area_data["form"]) > 0){
                            $data["content"]["config_form_modal"] = $this->load->view("_config_form",$work_area_data,true);            
                        }else{
                            $data["message"]["type"] = "error";
                            $data["message"]["text"] = "Fieldset config not found.";                                        
                        }
                    }else{
                        $data["content"]["config_form_modal"] = $this->load->view("_config_form",$work_area_data,true);            
                    }
                }else{
                    $data["message"]["type"] = "error";
                    $data["message"]["text"] = "Fieldset not found.";                
                }            
            }else{
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Invalid parameters.";
            }
        }
        echo json_encode($data);
    }
    
    public function delete_config($fieldset_config_id = null){
        auth_require_login(true);
        
        $data = array();
        if($fieldset_config_id){
            $this->load->database();
            $this->db->delete('fieldset_config', array('fieldset_config_id' => $fieldset_config_id));
            $data["content"]["table_container"] = $this->load->view("_table",array("fieldsets"=>$this->get_table_data()),true);            
            $data["message"]["type"] = "warning";
            $data["message"]["text"] = "Fieldset config deleted.";                        
        }else{
            $data["message"]["type"] = "error";
            $data["message"]["text"] = "Invalid parameters.";            
        }
        echo json_encode($data);
    }
}
