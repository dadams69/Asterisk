<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

get_instance()->load->library("session");

if (!isset($_SESSION)) {
    session_start();
}
            
if(! function_exists('auth_get_admin_user_id')){
    function auth_get_admin_user_id(){
        if (isset($_SESSION["asterisk"]) && isset($_SESSION["asterisk"]["admin_user_id"])) {
            return $_SESSION["asterisk"]["admin_user_id"];
        }
        return false;
    }
}

if(! function_exists('auth_is_logged')){
    function auth_is_logged(){
        if (auth_get_admin_user_id()) {
            return true;
        } else {
            return false;
        }
    }
}

if(! function_exists('auth_require_login')){
    function auth_require_login($super_admin = false){
        if(!auth_is_logged() || ($super_admin == true && !auth_is_super_admin())){
            $_SESSION["asterisk"]["_redirect_url_"] = uri_string();
            redirect('/asterisk', 'refresh');
        }
    }
}

if(! function_exists('auth_is_super_admin')){
    function auth_is_super_admin(){
        if (auth_get_admin_user_id()){ 
            $CI =& get_instance();
            $CI->load->database();
            $CI->db->select("super_admin");
            $CI->db->from("admin_user");
            $CI->db->where("admin_user_id", auth_get_admin_user_id());
            $query = $CI->db->get();
            if ($query->num_rows() > 0) {
                if($query->row()->super_admin == "1"){
                    return true;
                }
            }                
            return false;
        }else{
            return false;
        }
    }
}

if(! function_exists('autologin')){
    function autologin() {
       if  (!auth_is_logged()) {
            
            $CI =& get_instance();
            
            $admin_user_id = $ci->input->cookie('admin_user_id', TRUE);
            $admin_user_hash = $ci->input->cookie('admin_user_hash', TRUE);

            if ($admin_user_id && $admin_user_hash) {
                $CI->load->database();
                $CI->db->select("password");
                $CI->db->from("admin_user");
                $CI->db->where("admin_user_id", $admin_user_id);
                $CI->db->where("status", "ACTIVE");
                
                $query = $CI->db->get();
                
                $password = null;
                if ($query->num_rows() > 0) {
                    $password = $query->row()->password;
                }
                
                if ($password && (md5("SALTY" . $password) == $admin_user_hash) )  { 
                    $_SESSION["asterisk"]["admin_user_id"] = $admin_user_id;
                    return true;
                } else {
                    $this->load->helper('cookie');
                    $cookie = array(
                        'name'   => 'admin_user_id',
                        'value'  => '' ,
                        'expire' => '0',
                        'path'   => '/',
                    );
                    $this->input->set_cookie($cookie);
                    $cookie = array(
                        'name'   => 'admin_user_hash',
                        'value'  => '' ,
                        'expire' => '0',
                        'path'   => '/',
                    );
                    $this->input->set_cookie($cookie);
                }
            }
        }
   }
}