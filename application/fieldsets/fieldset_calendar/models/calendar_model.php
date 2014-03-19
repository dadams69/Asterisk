<?php
require_once APPPATH.'asterisk/asterisk_content/models/base_fieldset_model.php';

class calendar_model extends base_fieldset_model {
    
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->fieldset_key = "calendar";
        $this->auxillary_tables = array("fieldset_calendar_data");
    }

    function validate($form) {
        $validation = array();
        
        if (isset($form["fieldset_calendar_data"])) {
            foreach ($form["fieldset_calendar_data"] as $key => $arr_elm) {
                if(getdate_if_set("m/d/Y",$arr_elm, "start_date",null) && getdate_if_set("m/d/Y",$arr_elm, "end_date",null) && (getdate_if_set("m/d/Y",$arr_elm, "start_date",null) > getdate_if_set("m/d/Y",$arr_elm, "end_date",null))) {
                    $validation["fields"]["fieldset_calendar_data"][$key]["date"]["type"] = "error";
                    $validation["fields"]["fieldset_calendar_data"][$key]["date"]["message"] = "Dates are invalid.";                    
                }
            }
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

    function array_to_db($fieldset_id,$form){

        $CI = & get_instance();
        $CI->load->database();                
        $CI->db->select('fieldset_calendar_data.fieldset_calendar_data_id');                    
        $CI->db->from('fieldset_calendar_data');
        $CI->db->where("fieldset_calendar_data.fieldset_calendar_id",$fieldset_id);
        
        $query = $CI->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $fieldset_calendar_data){
                if(!isset($form["fieldset_calendar_data"]) || !isset($form["fieldset_calendar_data"][$fieldset_calendar_data->fieldset_calendar_data_id])){
                    $CI->db->where('fieldset_calendar_data.fieldset_calendar_data_id', $fieldset_calendar_data->fieldset_calendar_data_id);
                    $CI->db->where('fieldset_calendar_id',$fieldset_id);
                    $CI->db->delete('fieldset_calendar_data');                
                }
            }
        }
        
        if (isset($form["fieldset_calendar_data"]) && is_array($form["fieldset_calendar_data"])) {
            
            foreach($form["fieldset_calendar_data"] as $fieldset_calendar_data) {        
                    $CI->db->select('*');
                    $CI->db->from('fieldset_calendar_data');
                    $CI->db->where('fieldset_calendar_data_id',$fieldset_calendar_data["fieldset_calendar_data_id"]);
                    $CI->db->where('fieldset_calendar_id',$fieldset_id);
                    $query = $CI->db->get();

                    
                    if(is_numeric($fieldset_calendar_data["day"]) || $fieldset_calendar_data["day"] == "*"){
                        $fieldset_calendar_data["day_of_month"] = $fieldset_calendar_data["day"];
                        $fieldset_calendar_data["day_of_week"] = "*";
                    }else{
                        $fieldset_calendar_data["day_of_month"] = "*";
                        if($fieldset_calendar_data["day"] == "EVERY"){
                            $fieldset_calendar_data["day"] = "";
                        }
                        $fieldset_calendar_data["day_of_week"] = $fieldset_calendar_data["day_secondary"].$fieldset_calendar_data["day"];
                    }
                    
                    if($fieldset_calendar_data["am_pm"] == "12"){
                        $fieldset_calendar_data["hour"] = $fieldset_calendar_data["hour"] + 12;
                        if($fieldset_calendar_data["hour"] == 24){
                            $fieldset_calendar_data["hour"] = "12";
                        }
                    }else{
                        if($fieldset_calendar_data["hour"] == "12"){
                            $fieldset_calendar_data["hour"] = "0";
                        }
                    }                    
                    
                    if($fieldset_calendar_data["year"] == ""){
                        $fieldset_calendar_data["year"] = "*";
                    }
                    
                   
                    $start_date = DateTime::createFromFormat("m/d/Y H:i:s", $fieldset_calendar_data["start_date"]." 00:00:00");
                    $end_date = DateTime::createFromFormat("m/d/Y H:i:s", $fieldset_calendar_data["end_date"]." 23:59:59");
                    
                    $min_possible_date = null;
                    $max_possible_date = null;
                    if($fieldset_calendar_data["year"] != "*"){
                        $min_possible_date = new DateTime($fieldset_calendar_data["year"]."-01-01 00:00:00");
                        $max_possible_date = new DateTime($fieldset_calendar_data["year"]."-12-31 23:59:59");
                        if($fieldset_calendar_data["month"] != "*"){
                            $last_day = cal_days_in_month(CAL_GREGORIAN, $fieldset_calendar_data["month"], $fieldset_calendar_data["year"]);
                            $min_possible_date = new DateTime($fieldset_calendar_data["year"]."-".$fieldset_calendar_data["month"]."-01  00:00:00");
                            $max_possible_date = new DateTime($fieldset_calendar_data["year"]."-".$fieldset_calendar_data["month"]."-".$last_day." 23:59:59");
                            if(is_numeric($fieldset_calendar_data["day"])){
                                $min_possible_date = new DateTime($fieldset_calendar_data["year"]."-".$fieldset_calendar_data["month"]."-".$fieldset_calendar_data["day"]." 00:00:00");
                                $max_possible_date = new DateTime($fieldset_calendar_data["year"]."-".$fieldset_calendar_data["month"]."-".$fieldset_calendar_data["day"]." 23:59:59");                                
                            }
                        }
                    }
                    if($min_possible_date && $max_possible_date){
                        if(!$start_date || $start_date < $min_possible_date){
                            if($min_possible_date < $end_date || !$end_date){
                                $fieldset_calendar_data["start_date"] = $min_possible_date->format("Y-m-d H:i:s");
                            }else{
                                $fieldset_calendar_data["start_date"] = $end_date->format("Y-m-d H:i:s");
                            }
                        }else{
                            $fieldset_calendar_data["start_date"] = $start_date->format("Y-m-d H:i:s");
                        }
                        if(!$end_date || $end_date > $max_possible_date){
                            if($max_possible_date > $start_date || !$start_date){
                                $fieldset_calendar_data["end_date"] = $max_possible_date->format("Y-m-d H:i:s");
                            }else{
                                $fieldset_calendar_data["end_date"] = $start_date->format("Y-m-d H:i:s");
                            }
                        }else{
                            $fieldset_calendar_data["end_date"] = $end_date->format("Y-m-d H:i:s");
                        }                        
                    }else{
                        $fieldset_calendar_data["start_date"] = convert_string_date_format("m/d/Y H:i:s", "Y-m-d H:i:s", $fieldset_calendar_data["start_date"]." 00:00:00", null);
                        $fieldset_calendar_data["end_date"] = convert_string_date_format("m/d/Y H:i:s", "Y-m-d H:i:s", $fieldset_calendar_data["end_date"]." 23:59:59", null);                                            
                    }
                    
                    
                    if ($query->num_rows()== 0) {
                        $CI->db->insert("fieldset_calendar_data", array("fieldset_calendar_id"=>$fieldset_id,
                                    "start_date"=>$fieldset_calendar_data["start_date"],
                                    "end_date"=>$fieldset_calendar_data["end_date"],
                                    "year"=>$fieldset_calendar_data["year"],
                                    "month"=>$fieldset_calendar_data["month"],
                                    "day_of_month"=>$fieldset_calendar_data["day_of_month"],
                                    "day_of_week"=>$fieldset_calendar_data["day_of_week"],
                                    "hour"=>$fieldset_calendar_data["hour"],
                                    "minute"=>$fieldset_calendar_data["minute"],
                                    "duration"=>$fieldset_calendar_data["duration"]
                                )
                            );
                    }else{
                        $CI->db->where('fieldset_calendar_data_id',$fieldset_calendar_data["fieldset_calendar_data_id"]);
                        $CI->db->where('fieldset_calendar_id',$fieldset_id);
                        $CI->db->update("fieldset_calendar_data", array(
                                    "start_date"=>$fieldset_calendar_data["start_date"],
                                    "end_date"=>$fieldset_calendar_data["end_date"],
                                    "year"=>$fieldset_calendar_data["year"],
                                    "month"=>$fieldset_calendar_data["month"],
                                    "day_of_month"=>$fieldset_calendar_data["day_of_month"],
                                    "day_of_week"=>$fieldset_calendar_data["day_of_week"],
                                    "hour"=>$fieldset_calendar_data["hour"],
                                    "minute"=>$fieldset_calendar_data["minute"],
                                    "duration"=>$fieldset_calendar_data["duration"]                            
                                )
                            );
                    }
            }   
        }
        $this->mark_version_as_modified($this->get_version_id($fieldset_id));
    }
    
    function db_to_array($fieldset_id){
        $form = array();
        $CI = & get_instance();
        $CI->load->database();
        $CI->db->select('fieldset_calendar_data.fieldset_calendar_data_id,fieldset_calendar_data.start_date,fieldset_calendar_data.end_date,fieldset_calendar_data.year,fieldset_calendar_data.month,fieldset_calendar_data.day_of_month,fieldset_calendar_data.day_of_week,fieldset_calendar_data.hour,fieldset_calendar_data.minute,fieldset_calendar_data.duration');
        $CI->db->from('fieldset_calendar_data as fieldset_calendar_data');
        $CI->db->where('fieldset_calendar_data.fieldset_calendar_id', $fieldset_id);
        $CI->db->order_by('fieldset_calendar_data.start_date','ASC');
        
        $form["fieldset_calendar_id"] = $fieldset_id;
        $form["fieldset_calendar_data"] = array();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach($query->result() as $result){
                $arr_elm["fieldset_calendar_data_id"] = get_object_property($result, "fieldset_calendar_data_id");
                $arr_elm["start_date"] = convert_string_date_format("Y-m-d H:i:s","m/d/Y",get_object_property($result, "start_date"));
                $arr_elm["end_date"] = convert_string_date_format("Y-m-d H:i:s","m/d/Y",get_object_property($result, "end_date"));
                $arr_elm["year"] = get_object_property($result, "year");
                $arr_elm["month"] = get_object_property($result, "month");
                
                $arr_elm["day_of_month"] = get_object_property($result, "day_of_month");
                $arr_elm["day_of_week"] = get_object_property($result, "day_of_week");
                
                
                if($arr_elm["day_of_month"] == "*" && $arr_elm["day_of_week"] == "*"){
                    $arr_elm["day"] = "*";
                    $arr_elm["day_secondary"] = "";                    
                }else{
                    if($arr_elm["day_of_month"] == "*"){
                        if(strstr($arr_elm["day_of_week"],"#")){
                            $day_of_week_array = explode("#",$arr_elm["day_of_week"]);
                            $arr_elm["day"] = "#".$day_of_week_array[1];
                            $arr_elm["day_secondary"] = $day_of_week_array[0];
                        }elseif(strstr($arr_elm["day_of_week"],"L")){
                            $day_of_week_array = explode("L",$arr_elm["day_of_week"]);
                            $arr_elm["day"] = "L";
                            $arr_elm["day_secondary"] = $day_of_week_array[0];
                        }else{
                            $arr_elm["day"] = "EVERY";
                            $arr_elm["day_secondary"] = $arr_elm["day_of_week"];                        
                        }                        
                    }else{
                        $arr_elm["day"] = $arr_elm["day_of_month"];
                        $arr_elm["day_secondary"] = "";                                            
                    }
                }

                $arr_elm["hour"] = get_object_property($result, "hour");
                $arr_elm["minute"] = get_object_property($result, "minute");
                $arr_elm["duration"] = get_object_property($result, "duration");
                if($arr_elm["hour"] >= 12){
                    if($arr_elm["hour"] > 12){
                        $arr_elm["hour"] = $arr_elm["hour"] - 12;
                    }
                    $arr_elm["am_pm"] = "12";
                }else{
                    if($arr_elm["hour"] == 0){
                        $arr_elm["hour"] = 12;
                    }
                    $arr_elm["am_pm"] = "0";
                }
                $form["fieldset_calendar_data"][$arr_elm["fieldset_calendar_data_id"]] = $arr_elm;
            }
        }
        return $form;                    
    }
  
}