<?php
/**
 * @author jaldaz
 */
class Asterisk_Admin_User extends MX_Controller{
    
    private $base;
    
    public function __construct() {
        parent::__construct();
        auth_require_login(true);        
        $this->base = "asterisk_admin_user";
        $this->load->model("admin_user_model", "model");
    }

    /*
     * This function is responsible for displaying the list data
     */
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

        $data = $this->model->load_table_data($query_array);
        $partials = array();

        if (isset($data["message"])) {
            $partials["message"] = $data["message"];
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
    
    public function form($admin_user_id = null) {
        $data = array();
        $form = $this->input->post("form");
        $this->load->database();        
        if ($form) {

            // MAKE SURE POST IS VALID
            $validation = $this->model->validate($form);
            if ($validation["error"]) {
                $data["validation"] = $validation;
                if (isset($validation["message"])) {
                    $data["message"] = $validation["message"];
                }
                $data["form"] = $form;
                $this->load->view('form', $data);
                return;
            }

            // UPDATE OBJECT WITH POST VALUES
            $admin_user_id = $this->model->array_to_db($admin_user_id, $form);
            if ($admin_user_id == false) {
                $data["message"] = "There is an error on the proccess";
                $data["form"] = $form;
                $data["validation"] = null;
                $this->load->view('form', $data);
                return;
            }

            $data["message"]["type"] = "success";
            $data["message"]["text"] = "Admin user saved.";

        } 

        $data["form"] = $this->model->db_to_array($admin_user_id);
        $data["validation"] = null;
        $this->load->view('form', $data);
        return;
    }

    
    public function set_status($status_parameter){
        
        $query_array = decode_array($this->input->get());

        $error_executing = false;
        $id_set = false;
        
        $status = null;
        if (in_array(strtoupper($status_parameter), array("ACTIVE","INACTIVE"))) {
            $status = strtoupper($status_parameter);
        }
        if(isset($query_array["id"]) && $status) {
            
            $id_set = true;
            try{
                $this->load->database();
                $this->db->where_in('admin_user_id',$query_array["id"]);
                $this->db->update('admin_user',array("status"=>$status));            
            }catch(Exception $e){
                $error_executing = true;
            }
            unset($query_array["id"]);
        }
        
        $data = $this->model->load_table_data($query_array);
        
        $partials = array();
        
        if ($error_executing || !$status) {
            $partials["message"] = array("type"=>"error", "text"=>"Unable to update record.");
        } else if (!$id_set) {
            $partials["message"] = array("type"=>"error", "text"=>"No admin user selected.");
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
    
    public function delete(){
        
        $query_array = decode_array($this->input->get());

        $error_executing = false;
        $id_set = false;
        if(isset($query_array["id"])) {
            try{
                $id_set = true;
                $this->load->database();
                $this->db->where_in('admin_user_id',$query_array["id"]);
                $this->db->delete('admin_user');
            }catch(Exception $e){
                $error_executing = true;
            }
            unset($query_array["id"]);
        }
        
        $data = $this->model->load_table_data($query_array);
        
        $partials = array();
        
        if ($error_executing) {
            $partials["message"] = array("type"=>"error", "text"=>"Unable to delete record.");
        } else if (!$id_set) {
            $partials["message"] = array("type"=>"error", "text"=>"No admin user selected.");
        } else {
            $partials["message"] = array("type"=>"success", "text"=>"Success.");
        }        

        $partials["content"]["search"] = $this->load->view("index/_search", $data, true);
        $partials["content"]["pagination"] = $this->load->view("/index/_pagination", $data, true);
        $partials["content"]["table"] = $this->load->view("/index/_table", $data, true);

        if ($this->input->is_ajax_request()) {
            header("Cache-Control: no-store, no-cache, must-revalidate");
            echo json_encode($partials);
            return;
        } else {
            $partials["content"]["header"] = $this->load->view("/index/_header", $data, true);
            $partials["content"]["controls"] = $this->load->view("/index/_controls", $data, true);
            $this->load->view("/index", $partials);
        }        
    }
    
   
}