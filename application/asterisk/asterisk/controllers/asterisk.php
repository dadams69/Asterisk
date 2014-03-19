<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Asterisk extends CI_Controller {
    public function __construct() {
        parent::__construct();
        if (!isset($_SESSION)) {
            session_start();
        }
    }

    public function index() {
        
        if(!auth_is_logged()){
            $form = $this->input->post('form');
            if($form){
                $admin_user_id = null;
                $this->load->database();
                $this->db->select('admin_user_id');
                $this->db->from('admin_user');
                $this->db->where('email', $form['email']);
                $this->db->where('password', md5($form['password'])); 
                $this->db->where("status", "ACTIVE");
                
                $query = $this->db->get();
                
                if ($query->num_rows() > 0)
                { 
                    $row = $query->row(); 
                    $admin_user_id = $row->admin_user_id;
                }
                      
                $data["validation"] = array();
                if($admin_user_id){
                    $this->db->set('last_login_at', 'NOW()', FALSE); 
                    $this->db->where('admin_user_id', $admin_user_id);
                    $this->db->update('admin_user'); 

                    $_SESSION["asterisk"]["admin_user_id"] = $admin_user_id;
                    
                    if (isset($_SESSION["asterisk"]) && isset($_SESSION["asterisk"]["_redirect_url_"])) {
                        redirect($_SESSION["asterisk"]["_redirect_url_"]);
                        unset($_SESSION["asterisk"]["_redirect_url_"]);
                        return;
                    } else {
                        redirect("/asterisk");
                    }
                }else{
                    $data["validation"]["fields"]["email"]["message"]["type"] = "error";
                    $data["validation"]["fields"]["email"]["message"]["text"] = "Invalid Login.";                                            
                    $data["validation"]["fields"]["password"]["message"]["type"] = "error";
                    $data["validation"]["fields"]["password"]["message"]["text"] = "";                                                                
                }
                $data["form"] = $form;
                $this->load->view("login",$data);
            }else{
                $data["form"] = array();
                $data["validation"] = array();
                $this->load->view("login",$data);
            }
        }else{
            $this->load->view("welcome");
        }
    }
    
    public function logout(){
        unset($_SESSION["asterisk"]);
        redirect('/asterisk');        
    }
    
     public function environment($environment = "PRD"){
        set_environment($environment);
        redirect('/asterisk');        
    }
    
    public function now(){
        $form = $this->input->post('form');
        if ($form) {
            $now = getdate_if_set("m/d/Y H:i:s", $form, "now_date");
            var_dump($now);
            if($now) {
                set_now($now->format("Y-m-d H:i:s"));
            } else {
                set_now(null);
            }
        } else {
            set_now(null);
        }
        
        redirect('/asterisk');        
    }
}
