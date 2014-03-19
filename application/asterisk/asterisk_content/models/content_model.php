<?php

class content_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->database();
    }

    public function delete($content_id) {
        $this->db->select("version_id");
        $this->db->from("version");
        $this->db->where("content_id", $content_id);
        $query = $this->db->get();
        foreach($query->result() as $result) {
            $this->delete_version($content_id, $result->version_id);
        }
        $this->db->where("content_id", $content_id);
        $this->db->delete("content");
    }
    
    public function delete_version($content_id, $version_id) {
        $fieldsets = $this->get_fieldset_names();

        foreach ($fieldsets as $fieldset) {
            $this->load->model('fieldset_' . $fieldset . '/' . $fieldset . '_model');
            $fieldset = $fieldset . '_model';
            $this->$fieldset->delete_all($version_id);
        }
        
        $this->db->where('version_id', $version_id);
        $this->db->where('content_id', $content_id);
        $this->db->delete('version');
    }
    
    public function get_content_type_name($content_id) {
        $this->db->select('content_type.name');
        $this->db->from('content_type');
        $this->db->join('content', 'content.content_type_id = content_type.content_type_id','inner');
        $this->db->where('content.content_id', $content_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $row){
                return $row->name;
            }
        }
        return "Generic";
    }
    
    public function get_version_states($content_id){
        $version_states = array();
        $this->db->select('version.major_version, version_state.state');
        $this->db->from('version_state');
        $this->db->join('version', 'version.version_id = version_state.version_id','inner');
        $this->db->join('content', 'version.content_id = content.content_id','inner');
        $this->db->where('content.content_id', $content_id);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $row){
                $version_states[$row->state] = $row->major_version;
            }
        }
        return $version_states;
    }
    
    public function copy_all_fieldsets($old_version_id, $new_version_id) {
        $fieldsets = $this->get_fieldset_names();

        $fieldset_array = array();

        foreach ($fieldsets as $fieldset) {
            $this->load->model('fieldset_' . $fieldset . '/' . $fieldset . '_model');
            $fieldset = $fieldset . '_model';
            $items = $this->$fieldset->copy_all($old_version_id, $new_version_id);
        }

        // TODO: Order fields based on an orderable rank for each fieldset?  Drag and drop?\

        return true;
    }
    
    public function get_content_types(){
        $content_types = array();
        $this->db->select('name, content_type_id');
        $this->db->from('content_type');
        $this->db->order_by('rank');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $row){
                $content_types[$row->content_type_id] = $row->name;
            }
        }
        return $content_types;
    }
    
    private static function cmp($i1, $i2)
    {
        if (isset($i1["content_rank"])) {
            $a = $i1["content_rank"] * 10000 + $i1["fieldset_rank"] * 100 + $i1["fieldset_config_rank"];
        } else {
            $a = 990000 + $i1["fieldset_rank"] * 100 + $i1["fieldset_config_rank"];
        }
        if (isset($i2["content_rank"])) {
            $b = $i2["content_rank"] * 10000 + $i2["fieldset_rank"] * 100 + $i2["fieldset_config_rank"];
        } else {
            $b = 990000 + $i2["fieldset_rank"] * 100 + $i2["fieldset_config_rank"];
        }
        
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }
    
    public function get_fieldset_array($version_id) {
        $fieldsets = $this->get_fieldset_names();

        $fieldset_array = array();
        $fieldset_rank = 0;
        
        foreach ($fieldsets as $fieldset) {
            $this->load->model('fieldset_' . $fieldset . '/' . $fieldset . '_model');
            $fieldset = $fieldset . '_model';
            $items = $this->$fieldset->get_fieldset_array($version_id);
            foreach($items as $key => $value) {
                $value["fieldset_rank"] = $fieldset_rank;
                $fieldset_array[$key] = $value;
            }
            $fieldset_rank++;
        }

        // Get Content Type For Version Based on Content Table
        $this->db->select('content_type_id');
        $this->db->from('content');
        $this->db->join('version', 'version.content_id = content.content_id');
        $this->db->where('version_id', $version_id);
        $query = $this->db->get();
        $content_type_id = null;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $content_type_id = $row->content_type_id;
        } 
        if ($content_type_id) {
            // Get Rank from content_type_fieldset_config and set it in fieldset items.
            $this->db->select('fieldset_config_id, rank');
            $this->db->from('content_type_fieldset_config');
            $this->db->where('content_type_id', $content_type_id);
            $query = $this->db->get();
            foreach ($query->result() as $row) {
                if (isset($fieldset_array[$row->fieldset_config_id])) {
                    $item = $fieldset_array[$row->fieldset_config_id];
                    $item["content_rank"] = $row->rank;
                    $item["is_allowed"] = true;
                    $fieldset_array[$row->fieldset_config_id] = $item;
                }
            }
        } else {
            foreach($fieldset_array as $key=>$value) {
                $value["is_allowed"] = true;
                $fieldset_array[$key] = $value;
            }
        }
        usort($fieldset_array, "content_model::cmp");
        return $fieldset_array;
    }
    
    
    
    
    public function get_fieldset_names() {
        $fieldsets = array();
        
        /*$this->load->helper("directory_helper");
        $models = directory_map(APPPATH . "models/fieldset/", TRUE);
        
        foreach ($models as $model) {
            if (!is_array($model)) {
                $name = str_replace(EXT, "", $model);
                $name = str_replace("_model", "", $name);
                $fieldsets[] = $name;
            }
        }*/
        
        $this->load->database();
        $this->db->select('key');
        $this->db->from('fieldset');
        $this->db->order_by('fieldset.rank asc');
        $query = $this->db->get();

        foreach ($query->result() as $row)
        {
            $this->load->model('fieldset_' . $row->key . '/' . $row->key . '_model');
            $f = $row->key . '_model';
            if($this->$f->schema_exists()){
                $fieldsets[] = $row->key;
            }
        }

        return $fieldsets;
    }

    public function get_fieldsets_to_add($version_id) {
        // Get Content Type For Version Based on Content Table
        $this->db->select('content_type_id');
        $this->db->from('content');
        $this->db->join('version', 'version.content_id = content.content_id');
        $this->db->where('version_id', $version_id);
        $query = $this->db->get();
        $content_type_id = null;
        if ($query->num_rows() > 0) {
            $row = $query->row();
            $content_type_id = $row->content_type_id;
        }
        
        if ($content_type_id) {
            $this->db->select("fieldset_config.name as fieldset_config_name, fieldset.key as fieldset_key, fieldset_config.fieldset_config_id as fieldset_config_id");
            $this->db->from("fieldset_config");
            $this->db->join('fieldset', 'fieldset.fieldset_id = fieldset_config.fieldset_id');
            $this->db->join('content_type_fieldset_config', 'content_type_fieldset_config.fieldset_config_id = fieldset_config.fieldset_config_id');
            $this->db->where('content_type_fieldset_config.content_type_id', $content_type_id);
            $this->db->order_by('fieldset.rank asc, fieldset_config.rank');
        } else {
            $this->db->select("fieldset_config.name as fieldset_config_name, fieldset.key as fieldset_key, fieldset_config_id as fieldset_config_id");
            $this->db->from("fieldset_config");
            $this->db->join('fieldset', 'fieldset.fieldset_id = fieldset_config.fieldset_id');
            $this->db->order_by('fieldset.rank asc, fieldset_config.rank');
        }
        $query = $this->db->get();
        $result = array();
        
        foreach($query->result() as $config){
            $this->load->model('fieldset_' . $config->fieldset_key . '/' . $config->fieldset_key . '_model');
            $f = $config->fieldset_key . '_model';
            if($this->$f->schema_exists()){
                if (!$this->$f->version_has_fieldset_with_same_fieldset_config($version_id, $config->fieldset_config_id)) {
                    $result[] = $config; 
                }
            }            
        }
        return $result;
    }
    
    public function load_display_data($query_array) {

        //////////////////////////////////////////////////////////////
        //
        //  SEARCH VALIDATION AND PREPERATION
        //
        //////////////////////////////////////////////////////////////

        $data["search"] = array();

        $this->load->database();

        $this->db->start_cache();

        $this->db->select("content.content_id, 
                            content.name, 
                            content.created_at, 
                            content.last_modified_at, 
                            content_type.name as content_type_name,
                            production_version.version_id as production_version_version_id,
                            production_version.major_version as production_version_major_version,
                            staging_version.version_id as staging_version_version_id,
                            staging_version.major_version as staging_version_major_version,
                            max_version.version_id as max_version_version_id,
                            max_version.major_version as max_version_major_version
                            ");

        $this->db->from('content');
        $this->db->join("content_type as content_type","content.content_type_id = content_type.content_type_id","LEFT");
        $this->db->join("version_state as production_version_state", "production_version_state.content_id = content.content_id AND production_version_state.state='PRD'", "LEFT");
        $this->db->join("version as production_version", "production_version.version_id = production_version_state.version_id", "LEFT");
        $this->db->join("version_state as staging_version_state", "staging_version_state.content_id = content.content_id AND staging_version_state.state='STG'", "LEFT");
        $this->db->join("version as staging_version", "staging_version.version_id = staging_version_state.version_id", "LEFT");
        $this->db->join("version as max_version", "max_version.version_id = (SELECT version_id FROM version WHERE version.content_id = content.content_id ORDER BY major_version DESC LIMIT 1)", "LEFT", FALSE);
       

        if (isset($query_array["search"])) {
            $name = get_if_set($query_array, "search>name");
            if ($name != "") {
                $data["search"]["name"] = $name;
                $this->db->like("content.name", $name);
            }
            
            $content_type = get_if_set($query_array, "search>content_type");
            if ($content_type != "") {
                $data["search"]["content_type"] = $content_type;
                if ($content_type != "0") {
                    $this->db->where("content.content_type_id",$content_type);
                }else{
                    $this->db->where("content.content_type_id IS NULL");
                }
            }            
        }


        //////////////////////////////////////////////////////////////
        //
        //  ORDER BY PREPERATION
        //
        //////////////////////////////////////////////////////////////

        if (!isset($query_array["order_by_sort"])) {
            $query_array["order_by_sort"] = "content.content_id";
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