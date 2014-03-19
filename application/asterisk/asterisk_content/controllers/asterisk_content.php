<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Asterisk_Content extends MX_Controller {

    private $base;

    public function __construct() {
        parent::__construct();
        auth_require_login();
        $this->base = "asterisk_content";
        $this->load->model('content_model');
    }

    public function index() {

        $data = array();

        if (!$this->input->get()) {
            $q = $this->session->userdata($this->base . "_query_array");
            if ($q) {
                $query_array = $q;
            } else {
                $query_array = array();
            }
        } else {
            $query_array = decode_array($this->input->get());
            $this->session->set_userdata($this->base . "_query_array", $query_array);
        }

        $data = $this->content_model->load_display_data($query_array);
        $partials = array();

        if (isset($data["message"])) {
            $partials["message"] = $data["message"];
        }

        $data["content_types"] = $this->content_model->get_content_types();
        $partials["content"]["search"] = $this->load->view("index/_search", $data, true);
        $partials["content"]["pagination"] = $this->load->view("index/_pagination", $data, true);
        $partials["content"]["table"] = $this->load->view("index/_table", $data, true);

        if ($this->input->is_ajax_request()) {
            header("Cache-Control: no-store, no-cache, must-revalidate");
            echo json_encode($partials);
            return;
        } else {
            $partials["content"]["header"] = $this->load->view("index/_header", $data, true);
            $partials["content"]["controls"] = $this->load->view("index/_controls", $data, true);
            $this->load->view("index", $partials);
        }
    }

    public function form($content_id = null, $version_id = null) {
        $CI = & get_instance();
        $CI->load->database();
        
        if($content_id == "create") {
            if($version_id) {
                $content_type_id = $version_id;
            }
            $content_id = null;
            $version_id = null;
        }

        if (!$content_id || !$version_id) {
            if (!$content_id) {
                $content = array();
                $content["name"] = "Untitled";
                if ($content_type_id) {
                    $content["content_type_id"] = $content_type_id;
                }
                $CI->db->set("last_modified_at", "NOW()", FALSE);
                $CI->db->insert('content', $content);
                $content_id = $this->db->insert_id();
            }

            if (!$version_id) {
                $CI->db->select('version_id');
                $CI->db->from('version');
                $CI->db->where('content_id', $content_id);
                $CI->db->order_by('major_version desc');
                $CI->db->limit(1);
                $query = $CI->db->get();
                if ($query->num_rows() > 0) {
                    $version_id = $query->row('version_id');
                } else {
                    $version = array();
                    $version["content_id"] = $content_id;
                    $CI->db->set("last_modified_at", "NOW()", FALSE);
                    $version["major_version"] = 1;
                    $CI->db->insert('version', $version);
                    $version_id = $this->db->insert_id();
                }
            }
            
            if ($content_type_id) {
                $this->db->select('content_type_fieldset_config.fieldset_config_id, fieldset.key');
                $this->db->from('content_type_fieldset_config');
                $this->db->join('fieldset_config', 'fieldset_config.fieldset_config_id = content_type_fieldset_config.fieldset_config_id');
                $this->db->join('fieldset', 'fieldset.fieldset_id = fieldset_config.fieldset_id');
                $this->db->where('content_type_fieldset_config.content_type_id', $content_type_id);
                $this->db->order_by('content_type_fieldset_config.rank');
                $query = $this->db->get();
                if ($query->num_rows() > 0) {
                    foreach($query->result() as $row){
                        $fieldset = $row->key;
                        $this->load->model('fieldset_' . $fieldset . '/' . $fieldset . '_model');
                        $fieldset = $fieldset . '_model';
                        $this->$fieldset->add($version_id, $row->fieldset_config_id);
                    }
                }
            }
                
            redirect('/asterisk_content/form/' . $content_id . '/' . $version_id);
        }

        $data = array();

        $data["fieldset"]["existing"] = $this->content_model->get_fieldset_array($version_id);
        $data["fieldset"]["list"] = $this->content_model->get_fieldsets_to_add($version_id);
        $data["content_id"] = $content_id;
        $data["version_id"] = $version_id;
        $data["content_type_name"] = $this->content_model->get_content_type_name($content_id);
        $data["content_types"] = $this->content_model->get_content_types();
        
        $CI->db->select("*");
        $CI->db->from("content");
        $CI->db->where("content_id", $content_id);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            $data["content"] = $query->row();
        } else {
            redirect('/asterisk_content/form/' . $content_id . '/' . $version_id);
        }
        
        $CI->db->select("*");
        $CI->db->from("version");
        $CI->db->where("version_id", $version_id);
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            $data["version"] = $query->row();
        } else {
            redirect('/asterisk_content/form/' . $content_id . '/' . $version_id);
        }
        
        $CI->db->select("version_id, created_at, last_modified_at, major_version");
        $CI->db->from("version");
        $CI->db->where("content_id", $content_id);
        $CI->db->order_by("major_version desc");
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            $data["versions"] = $query;
        } else {
            redirect('/asterisk_content/form/' . $content_id . '/' . $version_id);
        }
        $data["version_states"] = $this->content_model->get_version_states($content_id);
        $this->load->view("form", $data);
        
    }
    
    public function delete() {

        $query_array = decode_array($this->input->get());
        
        $error_executing = false;
        $id_set = false;
        if(isset($query_array["id"])) {
            
            $id_set = true;
            
            foreach($query_array["id"] as $content_id) {
                $this->content_model->delete($content_id);
            }
            unset($query_array["id"]);
        }
        
        $data = $this->content_model->load_display_data($query_array);
        $partials = array();

        if ($error_executing) {
            $partials["message"] = array("type"=>"error", "text"=>"Unable to delete record.");
        } else if (!$id_set) {
            $partials["message"] = array("type"=>"error", "text"=>"No content selected.");
        } else {
            $partials["message"] = array("type"=>"success", "text"=>"Success.");
        }        

        $partials["content"]["search"] = $this->load->view("index/_search", $data, true);
        $partials["content"]["pagination"] = $this->load->view("index/_pagination", $data, true);
        $partials["content"]["table"] = $this->load->view("index/_table", $data, true);

        if ($this->input->is_ajax_request()) {
            header("Cache-Control: no-store, no-cache, must-revalidate");
            echo json_encode($partials);
            return;
        } else {
            $partials["content"]["header"] = $this->load->view("index/_header", $data, true);
            $partials["content"]["controls"] = $this->load->view("index/_controls", $data, true);
            $this->load->view("index", $partials);
        }        
    }
    
    public function edit_content_name(){
        $form = $this->input->post("form");
        if($form){
            if(isset($form["name"]) && isset($form["content_id"])){
                $this->load->database();
                $this->db->set('name', $form["name"]); 
                $this->db->where('content_id', $form["content_id"]);
                $this->db->update('content');            


                $this->db->select("*");
                $this->db->from("content");
                $this->db->where("content_id", $form["content_id"]);
                $query = $this->db->get();
                if ($query->num_rows() > 0) {
                    $view_data["content"] = $query->row();
                    $data["content"]["header_container"] = $this->load->view("asterisk_content/form/_header_title", $view_data, true);        
                    $data["message"]["type"] = "success";
                    $data["message"]["text"] = "Content updated.";                                             
                }else{
                    $data["message"]["type"] = "error";
                    $data["message"]["text"] = "Content not found.";                         
                }                 
            }else{
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Missing parameters.";     
            }
        }else{
            $data["message"]["type"] = "error";
            $data["message"]["text"] = "Missing form.";     
        }
        echo json_encode($data);        
    }
    
    public function edit_content_type($content_id, $version_id, $content_type_id = null) {

        $this->load->database();
        $this->db->set('content_type_id', $content_type_id); 
        $this->db->where('content_id', $content_id);
        $this->db->update('content');
                
        $partials = array();

        $fieldset_navigation_data["fieldset"]["existing"] = $this->content_model->get_fieldset_array($version_id);
        $fieldset_navigation_data["fieldset"]["list"] = $this->content_model->get_fieldsets_to_add($version_id);
        $fieldset_navigation_data["version_id"] = $version_id;

        $content_type_date = array();
        $content_type_date["content_id"] = $content_id;
        $content_type_date["version_id"] = $version_id;
        $content_type_date["content_types"] = $this->content_model->get_content_types();
        $content_type_date["content_type_name"] = $this->content_model->get_content_type_name($content_id);
                
        
        $partials["message"]["type"] = "notice";
        $partials["message"]["text"] = "Content Type Updated";
        $partials["content"]["navigation"] = $this->load->view("asterisk_content/form/_fieldset_navigation", $fieldset_navigation_data, true);
        $partials["content"]["content_type_dropdown"] =  $this->load->view("asterisk_content/form/_content_type", $content_type_date, true);

        echo json_encode($partials);
    }
    
    public function copy_version($content_id = null, $version_id = null) {
        $CI = & get_instance();
        $CI->load->database();

        if (!$content_id || !$version_id) {
            redirect('/asterisk_content/');
        }
        
        $now = new DateTime();
        $now_string = $now->format("Y-m-d H:i:s");
        $version = array();
        $version["content_id"] = $content_id;
        $version["created_at"] = $now_string;
        $version["last_modified_at"] = $now_string;
        $this->db->set($version);
        $this->db->set('major_version',"(Select new_major_version FROM (Select (major_version) as new_major_version from version where content_id = $content_id ORDER BY major_version desc LIMIT 1) AS a) + 1",FALSE);
        $CI->db->insert('version');
        $new_version_id = $this->db->insert_id();
        
        $this->content_model->copy_all_fieldsets($version_id, $new_version_id);

        redirect('/asterisk_content/form/' . $content_id . '/' . $new_version_id);
        
    }
    
    public function delete_version($content_id = null, $version_id = null) {
        $this->load->database();

        if (!$content_id || !$version_id) {
            redirect('/asterisk_content/');
        }
        
        $this->content_model->delete_version($content_id, $version_id);
        
        redirect('/asterisk_content/form/' . $content_id);
        
    }

    public function set_version_state($version_id=null,$state=null, $set_new = "true"){
        if($version_id && $state){
            if($state == "PRD" || $state == "STG"){

                $this->load->database();

                $this->db->select('content_id');
                $this->db->from('version');
                $this->db->where('version_id', $version_id);
                $this->db->limit(1);
                $query = $this->db->get();
                if ($query->num_rows() > 0) {
                    $content_id = $query->row('content_id');                        

                    $this->db->select('version_state_id');
                    $this->db->from('version_state');
                    $this->db->where('version_state.content_id', $content_id);
                    $this->db->where('version_state.state', $state);
                    $this->db->limit(1);
                    $query = $this->db->get();
                    if ($query->num_rows() > 0) {
                        $version_state_id = $query->row('version_state_id');        
                        if($set_new == "true"){
                            $this->db->set('version_id', $version_id); 
                            $this->db->set('state', $state); 
                            $this->db->where('version_state_id', $version_state_id);
                            $this->db->update('version_state');             
                        }else{
                            $this->db->where('state', $state); 
                            $this->db->where('version_state_id', $version_state_id);
                            $this->db->delete('version_state');                
                        }
                    }else{ 
                        if($set_new == "true"){
                            $version_state = array();
                            $version_state["version_id"] = $version_id;
                            $version_state["content_id"] = $content_id;
                            $version_state["state"] = $state;
                            $this->db->insert('version_state', $version_state);
                        }
                    }


                    $this->db->select("version_id, created_at, last_modified_at, major_version");
                    $this->db->from("version");
                    $this->db->where("content_id", $content_id);
                    $this->db->order_by("major_version desc");
                    $query = $this->db->get();
                    $view_data["state"] = $state;

                    $view_data["versions"] = $query;    
                    $view_data["version_states"] = $this->content_model->get_version_states($content_id);

                    $data["content"][$state."_version_state"] = $this->load->view("asterisk_content/form/_version_state", $view_data, true);
                    $data["message"]["type"] = "success";
                    if($state == "PRD"){
                        $data["message"]["text"] = "Production Version has changed.";        
                    }elseif($state == "STG"){
                        $data["message"]["text"] = "Staging Version has changed.";        
                    }
                }else{
                    $data["message"]["type"] = "error";
                    $data["message"]["text"] = "Version not found.";   
                }
            }else{
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Invalid parameters.";                                    
            }
        }else{
            $data["message"]["type"] = "error";
            $data["message"]["text"] = "Missing parameters.";                    
        }
        echo json_encode($data);
    }
    
    public function delete_fieldset($fieldset, $fieldset_id) {

        $partials = array();

        $this->load->model('fieldset_' . $fieldset . '/' . $fieldset . '_model');
        $fieldset = $fieldset . '_model';

        $version_id = $this->$fieldset->get_version_id($fieldset_id);

        $this->$fieldset->delete($fieldset_id);

        $fieldset_navigation_data["fieldset"]["existing"] = $this->content_model->get_fieldset_array($version_id);
        $fieldset_navigation_data["fieldset"]["list"] = $this->content_model->get_fieldsets_to_add($version_id);
        $fieldset_navigation_data["version_id"] = $version_id;

        $partials["message"]["type"] = "notice";
        $partials["message"]["text"] = "Fieldset Deleted";
        $partials["content"]["navigation"] = $this->load->view("asterisk_content/form/_fieldset_navigation", $fieldset_navigation_data, true);
        $partials["content"]["work_area"] = '<section><div class="page-header"><h1>Fieldset Deleted</h1></div>';

        echo json_encode($partials);
    }

    public function add_fieldset($fieldset, $version_id, $fieldset_config_id) {
        $partials = array();

        $params = $this->input->get();

        $this->load->model('fieldset_' . $fieldset . '/' . $fieldset . '_model');
        $fieldset = $fieldset . '_model';

        $message = $this->$fieldset->add($version_id, $fieldset_config_id);

        $fieldset_navigation_data["fieldset"]["existing"] = $this->content_model->get_fieldset_array($version_id);
        $fieldset_navigation_data["fieldset"]["list"] = $this->content_model->get_fieldsets_to_add($version_id);
        $fieldset_navigation_data["version_id"] = $version_id;

        $partials["message"] = $message;
        $partials["content"]["navigation"] = $this->load->view("asterisk_content/form/_fieldset_navigation", $fieldset_navigation_data, true);

        echo json_encode($partials);
    }

}
