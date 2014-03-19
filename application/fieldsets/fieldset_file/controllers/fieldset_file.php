<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Fieldset_File extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("file_model", "model");
    }

    public function form($fieldset_id) {

        $data = array();
        $work_area_data["validation"] = array();
        $work_area_data["form"] = $this->model->db_to_array($fieldset_id);

        $data["content"]["work_area"] = $this->load->view("form", $work_area_data, true);
        echo json_encode($data);
    }

    public function upload_file($fieldset_id) {
        $request = array();
        if ($_FILES) {
            $request = array_merge($request, array("FILES" => $_FILES));
        }
        if (isset($request['FILES']['file_' . $fieldset_id])) {
            try {
                $form_image = $request['FILES']['file_' . $fieldset_id];

                if ($form_image["tmp_name"] != "") {

                    $this->load->database();
                    $fieldset_file_data["filename"] = substr($form_image["name"], 0, strrpos($form_image["name"], '.'));
                    $fieldset_file_data["filename"] = str_replace(" ", "_", $fieldset_file_data["filename"]);
                    $fieldset_file_data["extension"] = strtolower(substr($form_image["name"], strrpos($form_image["name"], '.') + 1));
                    $fieldset_file_data["title"] = substr($form_image["name"], 0, strrpos($form_image["name"], '.'));
                    $fieldset_file_data["description"] = "";
                    $fieldset_file_data["credit"] = "";
                    $fieldset_file_data["keywords"] = "";
                    $fieldset_file_data["size"] = $form_image["size"];

                    if ($fieldset_file_data["extension"] == "jpg" || $fieldset_file_data["extension"] == "jpeg" || $fieldset_file_data["extension"] == "bmp" || $fieldset_file_data["extension"] == "png" || $fieldset_file_data["extension"] == "gif") {
                        $this->optimizeImage($form_image["tmp_name"]);
                        $imagesize = getimagesize($form_image["tmp_name"]);

                        $fieldset_file_data["image_size_key"] = "original";
                        $fieldset_file_data["image_height"] = $imagesize[1];
                        $fieldset_file_data["image_width"] = $imagesize[0];
                    }

                    $this->db->insert("fieldset_file_data", $fieldset_file_data);
                    $fieldset_file_data_id = $this->db->insert_id();

                    $this->db->insert("fieldset_file_mapping", array("fieldset_file_id" => $fieldset_id, "fieldset_file_data_id" => $fieldset_file_data_id));

                    $dir = dirname($_SERVER['SCRIPT_FILENAME']) . '/public/files/' . $fieldset_file_data_id . '/';
                    if (!file_exists($dir))
                        mkdir($dir, 0777, true);

                    move_uploaded_file($form_image["tmp_name"], dirname($_SERVER['SCRIPT_FILENAME']) . '/public/files/' . $fieldset_file_data_id . '/' . $fieldset_file_data["filename"] . "." . $fieldset_file_data["extension"]);

                    echo "[" . json_encode(array(
                        "fieldset_file_data_id" => $fieldset_file_data_id,
                        "url" => base_url("public/files/" . $fieldset_file_data_id . "/" . $fieldset_file_data["filename"] . "." . $fieldset_file_data["extension"]),
                        "extension" => $fieldset_file_data["extension"],
                        "name" => $fieldset_file_data["title"],
                        "size" => $this->model->get_file_size_string($fieldset_file_data["size"]),
                        "image_width" => get_if_set($fieldset_file_data, "image_width"),
                        "image_height" => get_if_set($fieldset_file_data, "image_height"),
                        "size_key_name" => $this->model->get_size_key_name($fieldset_id, get_if_set($fieldset_file_data, "image_size_key"))
                            )
                    ) . "]";
                }else
                    echo "[" . json_encode(array("error" => "Missing File.")) . "]";
            } catch (Exception $e) {
                echo "[" . json_encode(array("error" => $e->getMessage())) . "]";
            }
        }else
            echo "[" . json_encode(array("error" => "No File was uploaded.")) . "]";
    }

    public function delete_file($fieldset_id, $fieldset_file_data_id) {

        $file_data = $this->model->file_data_to_array($fieldset_file_data_id);
        if (count($file_data) > 0) {
            $this->db->where("fieldset_file_id", $fieldset_id);
            $this->db->where("fieldset_file_data_id", $fieldset_file_data_id);
            $this->db->delete("fieldset_file_mapping");

            $this->model->delete_non_used_files();

            echo json_encode(array("result" => "true", "message" => array("type" => "notice", "text" => "File deleted"),));
        } else {
            echo json_encode(array("result" => "false", "message" => array("type" => "error", "text" => "Error on file delete"),));
        }
    }

    public function copy_file($fieldset_id, $fieldset_file_data_id) {

        $file_data = $this->model->file_data_to_array($fieldset_file_data_id);
        if (count($file_data) > 0) {
            $this->load->database();
            $original_fieldset_file_data_id = $file_data["fieldset_file_data_id"];
            unset($file_data["fieldset_file_data_id"]);
            unset($file_data["previous_filename"]);

            $this->db->insert("fieldset_file_data", $file_data);
            $fieldset_file_data_id = $this->db->insert_id();

            $this->db->insert("fieldset_file_mapping", array("fieldset_file_id" => $fieldset_id, "fieldset_file_data_id" => $fieldset_file_data_id));

            $dir = dirname($_SERVER['SCRIPT_FILENAME']) . '/public/files/' . $fieldset_file_data_id . '/';
            if (!file_exists($dir))
                mkdir($dir, 0777, true);

            copy(dirname($_SERVER['SCRIPT_FILENAME']) . '/public/files/' . $original_fieldset_file_data_id . '/' . $file_data["filename"] . "." . $file_data["extension"], dirname($_SERVER['SCRIPT_FILENAME']) . '/public/files/' . $fieldset_file_data_id . '/' . $file_data["filename"] . "." . $file_data["extension"]);

            echo json_encode(array("result" => "true",
                "message" => array("type" => "notice", "text" => "File copied"),
                "file" => array(
                    "fieldset_file_data_id" => $fieldset_file_data_id,
                    "url" => base_url("public/files/" . $fieldset_file_data_id . "/" . $file_data["filename"] . "." . $file_data["extension"]),
                    "extension" => $file_data["extension"],
                    "name" => $file_data["title"],
                    "size" => $this->model->get_file_size_string($file_data["size"]),
                    "image_width" => get_if_set($file_data, "image_width"),
                    "image_height" => get_if_set($file_data, "image_height"),
                    "size_key_name" => $this->model->get_size_key_name($fieldset_id, get_if_set($file_data, "image_size_key"))
                    )));
        }else {
            echo json_encode(array("result" => "false", "message" => array("type" => "error", "text" => "Error on file copy"),));
        }
    }

    public function form_file_data($fieldset_file_id, $fieldset_file_data_id) {

        $data = array();

        $form = $this->input->post("form");
        if ($form) {
            $data["validation"] = $this->model->validate_data_information($form);
            if ($data["validation"]["error"]) {
                $data["message"]["type"] = "error";
                $data["message"]["text"] = "Check your errors.";
                $data["form"] = $form;
                $data["fieldset_file_id"] = $fieldset_file_id;
                echo json_encode(array("result" => "false", "modal_body" => $this->load->view("form_file_data", $data, true)));
            } else {
                $this->model->array_to_db($fieldset_file_data_id, $form);
                $data["message"]["type"] = "success";
                $data["message"]["text"] = "Main Content saved.";

                $file_data = $this->model->file_data_to_array($fieldset_file_data_id);

                echo json_encode(array("result" => "true",
                    "message" => array("type" => "success", "text" => "File data saved"),
                    "file" => array(
                        "fieldset_file_data_id" => $fieldset_file_data_id,
                        "url" => base_url("public/files/" . $fieldset_file_data_id . "/" . $file_data["filename"] . "." . $file_data["extension"]),
                        "extension" => $file_data["extension"],
                        "name" => $file_data["title"],
                        "size" => $this->model->get_file_size_string($file_data["size"]),
                        "image_width" => get_if_set($file_data, "image_width"),
                        "image_height" => get_if_set($file_data, "image_height"),
                        "size_key_name" => $this->model->get_size_key_name($fieldset_file_id, get_if_set($file_data, "image_size_key"))
                        )));
            }
        } else {
            $data["validation"] = array();
            $data["form"] = $this->model->file_data_to_array($fieldset_file_data_id);
            $data["fieldset_file_id"] = $fieldset_file_id;
            echo json_encode(array("modal_body" => $this->load->view("form_file_data", $data, true)));
        }
    }

    public function resize_image() {
        $form = $this->input->post("image_edit_form");

        $validate_data = $this->validate_edit($form["size_key"]);

        if (!$validate_data) {
            $size_key = $form['size_key'];
            //Crop Parameters
            $crop_x = $form['crop_x'];
            $crop_y = $form['crop_y'];
            $crop_width = $form['crop_width'];
            $crop_height = $form['crop_height'];
            //Resize Parameters
            $width = $form['image_width'];
            $height = $form['image_height'];

            $CI = & get_instance();


            $fieldset_file_data = $this->model->file_data_to_array($form['fieldset_file_data_id']);
            if (count($fieldset_file_data) == 0) {
                die(json_encode(array("result" => "false", "message" => "Error: Invalid Image Id.")));
            }

            $source_path = dirname($_SERVER['SCRIPT_FILENAME']) . '/public/files/' . $fieldset_file_data['fieldset_file_data_id'] . '/' . $fieldset_file_data["filename"] . "." . $fieldset_file_data["extension"];
            $imagesize = getimagesize($source_path);

            //Resize
            if ($imagesize[0] != $width || $imagesize[1] != $height) {
                $config['image_library'] = 'gd2';
                $config['source_image'] = $source_path;
                //$config['new_image'] = $new_image->getFullPath();
                //$source_path = $new_image->getFullPath();
                $config['maintain_ratio'] = TRUE;
                $config['width'] = $width;
                $config['height'] = $height;

                $CI->load->library('image_lib', $config);

                $CI->image_lib->resize();

                unset($config);
            }
            //CROP
            //Do Crop always, to avoid errors.
            //if($crop_x!=0 || $crop_y!=0 ||$crop_width!=$width || $crop_height!=$height){
            $config['image_library'] = 'gd2';
            $config['source_image'] = $source_path;
            //$config['new_image'] = $new_image->getFullPath();
            $config['maintain_ratio'] = FALSE;
            $config['x_axis'] = $crop_x;
            $config['y_axis'] = $crop_y;
            $config['width'] = $crop_width;
            $config['height'] = $crop_height;

            if (!isset($CI->image_lib))
                $CI->load->library('image_lib', $config);
            else {
                $CI->image_lib->clear();
                $CI->image_lib->initialize($config);
            }

            $CI->image_lib->crop();
            //}

            $new_imagesize = getimagesize($source_path);
            $fieldset_file_data["size"] = filesize($source_path);
            $fieldset_file_data["image_width"] = $new_imagesize[0];
            $fieldset_file_data["image_height"] = $new_imagesize[1];
            $fieldset_file_data["image_size_key"] = $size_key;

            unset($fieldset_file_data['previous_filename']);
            $this->db->where('fieldset_file_data_id', $fieldset_file_data['fieldset_file_data_id']);
            $this->db->update('fieldset_file_data', $fieldset_file_data);

            echo json_encode(array("result" => "true",
                "message" => array("type" => "success", "text" => "Image resize successful"),
                "file" => array(
                    "fieldset_file_data_id" => $fieldset_file_data['fieldset_file_data_id'],
                    "url" => base_url("public/files/" . $fieldset_file_data['fieldset_file_data_id'] . "/" . $fieldset_file_data["filename"] . "." . $fieldset_file_data["extension"]),
                    "extension" => $fieldset_file_data["extension"],
                    "name" => $fieldset_file_data["title"],
                    "size" => $this->model->get_file_size_string($fieldset_file_data["size"]),
                    "image_width" => get_if_set($fieldset_file_data, "image_width"),
                    "image_height" => get_if_set($fieldset_file_data, "image_height"),
                    "size_key_name" => $this->model->get_size_key_name($form["fieldset_file_id"], get_if_set($fieldset_file_data, "image_size_key"))
                )
            ));
        } else {
            echo json_encode(array("result" => "false", "message" => array("type" => "error", "text" => "Error on image resize"),));
        }
    }

    private function validate_edit($data) {
        /* $CI =& get_instance();
          $classname = "Model\Lantern\Content\Type\\".ucfirst(camelize($data['parent_library']));
          $size_keys = $classname::getImageKeySizes();
          $size_data = $size_keys[$data['size_key']];

          $error = "";
          //validate crop sizes
          if($data['crop_width'] < $size_data['width_min'])
          $error = $error."Crop Width is invalid.\n";
          if($data['crop_width'] > $size_data['width_max'])
          $error = $error."Crop Width is invalid.\n";
          if($data['crop_height'] < $size_data['height_min'])
          $error = $error."Crop Height is invalid.\n";
          if($data['crop_height'] > $size_data['height_max'])
          $error = $error."Crop Height is invalid.\n";

          //validate out of bounds
          if($data['crop_x'] + $data['crop_width'] > $data['image_width'])
          $error = $error."Crop Width out of bounds.\n";
          if($data['crop_y'] + $data['crop_height'] > $data['image_height'])
          $error = $error."Crop Height out of bounds.\n";

          //validate ratio
          if($size_data['ratio_width'] != '' && $size_data['ratio_height'] != '' ){
          if($data['crop_width'] < (($data['crop_height'] / $size_data['ratio_height'] * $size_data['ratio_width'])-1)
          || $data['crop_width'] > (($data['crop_height'] / $size_data['ratio_height'] * $size_data['ratio_width'])+1))
          $error = $error."Invalid Ratio, should be ".$size_data['ratio_width'].":".$size_data['ratio_height'].".\n";
          }

          if($error=="")
          return false;
          else
          return $error; */
        return false;
    }

    private function optimizeImage($image_path) {
        $imagesize = getimagesize($image_path);
        if ($imagesize[0] > 2400 || $imagesize[1] > 2400) {
            $CI = & get_instance();

            $config['image_library'] = 'gd2';
            $config['source_image'] = $image_path;
            $config['maintain_ratio'] = TRUE;
            $config['width'] = 2400;
            $config['height'] = 2400;

            if (!isset($CI->image_lib))
                $CI->load->library('image_lib', $config);
            else {
                $CI->image_lib->clear();
                $CI->image_lib->initialize($config);
            }

            $CI->image_lib->resize();
        }
    }

    public function get_files($fieldset_file_id) {
        $this->load->database();
        $this->db->select('fieldset_file_data.fieldset_file_data_id, fieldset_file_data.filename, fieldset_file_data.extension, fieldset_file_data.title, fieldset_file_data.size');
        $this->db->from('fieldset_file_data as fieldset_file_data');
        //TODO AVOID FILES THAT ARE ALREADY ADDED IN MAPPING TABLE
        //$CI->db->where('fieldset_file_mapping.fieldset_file_id !=', $fieldset_file_id);

        $files = array();
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $result) {
                $arr_elm = array();
                $arr_elm["fieldset_file_data_id"] = get_object_property($result, "fieldset_file_data_id");

                $arr_elm["label"] = get_object_property($result, "title") . " (" . $this->model->get_file_size_string(get_object_property($result, "size")) . ") - " . get_object_property($result, "filename") . "." . get_object_property($result, "extension");
                $files[] = $arr_elm;
            }
        }
        echo json_encode($files);
    }

    public function add_file($fieldset_file_id, $fieldset_file_data_id) {
        $file_data = $this->model->file_data_to_array($fieldset_file_data_id);
        if (count($file_data) > 0) {
            $this->load->database();
            $this->db->insert("fieldset_file_mapping", array("fieldset_file_id" => $fieldset_file_id, "fieldset_file_data_id" => $fieldset_file_data_id));

            echo json_encode(array("result" => "true",
                "message" => array("type" => "notice", "text" => "File added"),
                "file" => array(
                    "fieldset_file_data_id" => $fieldset_file_data_id,
                    "url" => base_url("public/files/" . $fieldset_file_data_id . "/" . $file_data["filename"] . "." . $file_data["extension"]),
                    "extension" => $file_data["extension"],
                    "name" => $file_data["title"],
                    "size" => $this->model->get_file_size_string($file_data["size"]),
                    "image_width" => get_if_set($file_data, "image_width"),
                    "image_height" => get_if_set($file_data, "image_height"),
                    "size_key_name" => $this->model->get_size_key_name($fieldset_file_id, get_if_set($file_data, "image_size_key"))
                    )));
        } else {
            echo json_encode(array("result" => "false", "message" => array("type" => "error", "text" => "Error on file add"),));
        }
    }

    public function partial_file($fieldset_file_data_id = null) {
        $this->load->database();
        
        $data = array();
        
        $file_data = array();
        if ($fieldset_file_data_id) {
            
            
            $this->db->select("*");
            $this->db->from("fieldset_file_data");
            $this->db->where("fieldset_file_data_id", $fieldset_file_data_id);

            $query = $this->db->get();
        
            if ($query->num_rows() == 1) {
                $file_data["file_data"] = $query->row();
            } else {
                $file_data["file_data"] = null;    
            }
        
        } else {
            $file_data["file_data"] = null;
        }
        $file_data["mode"] = "input";
        $data["content"]["file"] = $this->load->view("_file", $file_data, true);
        echo json_encode($data);
    }
    
    public function partial_file_select_modal($mode = "modal", $version_id = null) {
        
        $base_query = $this->input->post("base_query"); //type:img
        $custom_query = $this->input->post("custom_query");
        $limit_to_version = $this->input->post("limit_to_version");
        
        if ($limit_to_version == "false") {
            $limit_to_version = false;
        } else {
            $limit_to_version = true;
        }
        
        $extensions = array();
        $size_keys = array();
        $words = array();
        
        $query_parts = explode(" ", $base_query . ' ' . $custom_query);
        foreach($query_parts as $part) {
            $part = trim($part);
            if($part) {
                if (strpos($part, "type:") === 0) {
                    $val = substr($part, 5);
                    $vals = explode("|", $val);
                    foreach ($vals as $v) {
                        if (!in_array($v, $extensions)) {
                            $extensions[] = $v;
                        }
                    }
                } else if (strpos($part, "size:") === 0) {
                    $val = substr($part, 5);
                    $vals = explode("|", $val);
                    foreach ($vals as $v) {
                        if (!in_array($v, $size_keys)) {
                            $size_keys[] = $v;
                        }
                    }
                } else {
                    $v = $part;
                    if (!in_array($v, $words)) {
                        $words[] = $v;
                    }
                }
            }
        }
        $this->load->database();
        $data = array();
        $modal_data = array();
        
        $modal_data["limit_to_version"] = $modal_data;
        
        $modal_data["mode"] = $mode;
        $this->db->select("*");
        $this->db->from("fieldset_file_data");
        
        if($limit_to_version && $version_id) {
            $this->db->join("fieldset_file_mapping", "fieldset_file_data.fieldset_file_data_id = fieldset_file_mapping.fieldset_file_data_id");
            $this->db->join("fieldset_file", "fieldset_file.fieldset_file_id = fieldset_file_mapping.fieldset_file_id");
            $this->db->where("version_id", $version_id);
        }
        if(count($extensions) > 0) {
            $this->db->where("extension in ('" . implode("','", $extensions)  . "')");
        }
        
        if(count($size_keys) > 0) {
            $this->db->where("image_size_key in ('" . implode("','", $size_keys)  . "')");
        }
        
        if(count($words) > 0) {
            foreach ($words as $word) {
                $this->db->where("CONCAT(title,description,keywords,credit) LIKE '%" . $word . "%'");
            }
        }
        
        $this->db->order_by("fieldset_file_data.fieldset_file_data_id DESC");
        
        $query = $this->db->get();
        
        $modal_data["files"] = $query;
        
        if ($mode == "modal") {
            $data["content"]["modal"] = $this->load->view("_file_select_modal", $modal_data, true);
        } else {
            $data["content"]["results"] = $this->load->view("_file_select_modal", $modal_data, true);
        }
        echo json_encode($data);
    }

}