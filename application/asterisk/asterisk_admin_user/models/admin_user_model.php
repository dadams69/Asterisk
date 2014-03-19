<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

class admin_user_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function validate($form){
        $validation = array();
        
        $email = get_if_set($form, "email");
        if ($email == "") {
            $validation["fields"]["email"]["message"]["type"] = "error";
            $validation["fields"]["email"]["message"]["text"] = "Email is required.";
        }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $validation["fields"]["email"]["message"]["type"] = "error";
            $validation["fields"]["email"]["message"]["text"] = "Email is invalid.";            
        }
      
        $password = get_if_set($form, "password");
        $password_confirm = get_if_set($form, "password_confirm");

        if (get_if_set($form, "admin_user_id") == "" && $password == "") {
            $validation["fields"]["password"]["message"]["type"] = "error";
            $validation["fields"]["password"]["message"]["text"] = "A valid password is required.";
        } 
        if ($password != $password_confirm) {
            $validation["fields"]["password"]["message"]["type"] = "error";
            $validation["fields"]["password"]["message"]["text"] = "";            
            $validation["fields"]["password_confirm"]["message"]["type"] = "error";
            $validation["fields"]["password_confirm"]["message"]["text"] = "Passwords must match.";
        }
        
        if (isset($validation["fields"])) {
            $validation["message"]["type"] = "error";
            $validation["message"]["text"] = "Please check your form for errors.";
            $validation["error"] = true;
        } else {
            $validation["error"] = false;
        }

        return $validation;   
    }
    
    function array_to_db($id, $form){
        unset($form["admin_user_id"]);
        
        $data["admin_user"] = array();
        $data["admin_user"]["email"] = get_if_set($form,"email");
        if(get_if_set($form,"password") != ""){
            $data["admin_user"]["password"] = md5(get_if_set($form,"password"));
        }
        $data["admin_user"]["first_name"] = get_if_set($form,"first_name");
        $data["admin_user"]["last_name"] = get_if_set($form,"last_name");
        $data["admin_user"]["status"] = get_if_set($form,"status");
        $data["admin_user"]["super_admin"] = get_if_set($form,"super_admin");
        
        if($id){
            $this->db->where('admin_user_id', $id);
            $this->db->update('admin_user', $data["admin_user"]);            
            $admin_user_id = $id;
        }else{
            $this->db->insert('admin_user',$data["admin_user"]);
            $admin_user_id = $this->db->insert_id();
        }
        
        return $admin_user_id;
    }
    
    function db_to_array($id){
        $form = array();
        
        $query = $this->db->get_where('admin_user',array("admin_user_id"=>$id));
        if ($query->num_rows() > 0){
            $admin_user = $query->row();
            $form["admin_user_id"] = get_object_property($admin_user, "admin_user_id");
            $form["email"] = get_object_property($admin_user, "email");
            $form["last_login_at"] = get_object_property($admin_user, "last_login_at");
            $form["first_name"] = get_object_property($admin_user, "first_name");
            $form["last_name"] = get_object_property($admin_user, "last_name");
            $form["super_admin"] = get_object_property($admin_user, "super_admin");
            $form["status"] = get_object_property($admin_user, "status");
        }
        
        return($form);          
    }
    
     public function load_table_data($query_array) {

        //////////////////////////////////////////////////////////////
        //
        //  SEARCH VALIDATION AND PREPERATION
        //
        //////////////////////////////////////////////////////////////

        $data["search"] = array();

        $this->load->database();

        $this->db->start_cache();

        $this->db->select("admin_user.admin_user_id, 
                            admin_user.email, 
                            admin_user.last_login_at, 
                            admin_user.first_name, 
                            admin_user.last_name,
                            admin_user.super_admin,
                            admin_user.status,
                            ");

        $this->db->from('admin_user');

        if (isset($query_array["search"])) {
            $email = get_if_set($query_array, "search>email");
            if ($email != "") {
                $data["search"]["email"] = $email;
                $this->db->like("admin_user.email", $email);
            }
        }


        //////////////////////////////////////////////////////////////
        //
        //  ORDER BY PREPERATION
        //
        //////////////////////////////////////////////////////////////

        if (!isset($query_array["order_by_sort"])) {
            $query_array["order_by_sort"] = "admin_user.admin_user_id";
        }
        if (!isset($query_array["order_by_order"])) {
            $query_array["order_by_order"] = "DESC";
        }

        //////////////////////////////////////////////////////////////
        //
        //  TOTAL ROW CALCULATION
        //
        //////////////////////////////////////////////////////////////

        $total_rows = $this->db->count_all_results();

        //////////////////////////////////////////////////////////////
        //
        //  PAGINATION PREPERATION
        //
        //////////////////////////////////////////////////////////////

        $page = isset($query_array["page"]) ? $query_array["page"] : 1;
        $rows_per_page = isset($query_array["rows_per_page"]) ? $query_array["rows_per_page"] : 25;
        $total_pages = ceil($total_rows / $rows_per_page);

        if ($page > $total_pages) {
            if ($total_pages > 0)
                $page = $total_pages;
            else
                $page = 1;
        }

        $data["pagination"] = array();
        $data["pagination"]["query_array"] = $query_array;
        $data["pagination"]["total_rows"] = $total_rows;
        $data["pagination"]["page"] = $page;
        $data["pagination"]["total_pages"] = $total_pages;
        $data["pagination"]["rows_per_page"] = $rows_per_page;

        //////////////////////////////////////////////////////////////
        //
        //  DATA LOOKUP
        //
        //////////////////////////////////////////////////////////////

        $this->db->limit($rows_per_page, ($page - 1) * $rows_per_page);

        $this->db->order_by($query_array["order_by_sort"], $query_array["order_by_order"]);

        $results = $this->db->get();
        $this->db->stop_cache();
        $this->db->flush_cache();

        $data["table"] = array();
        $data["table"]["query_array"] = $query_array;
        $data["table"]["results"] = $results;

        $data["search"]["query_array"] = $query_array;

        //////////////////////////////////////////////////////////////
        //
        //  RETURN COMPLETE DATA
        //
        //////////////////////////////////////////////////////////////

        return $data;
    }

    
    
}