<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gender extends DT_CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("Mdl_gender"));
        $this->lang->load('gender');
    }

    //Index page
    public function index()
    {
        $data['extra_js'] = array(
            "js/plugins/tables/datatables/datatables.min.js",
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/plugins/forms/jquery.form.min.js"

        );

        $this->dt_ci_template->load("default", "Gender/v_gender", $data);
    }

    // ajax call to the data listing
    public function getGenderListing()
    {
        $this->load->library('datatables');
        $this->datatables->select("g.gender_id,g.gender_name");
        $this->datatables->from("tbl_gender as g");
        echo $this->datatables->generate();

    }

    //insert and update function
    public function manage($genderId = '') // change here manage
    {

        $data['extra_js'] = array(
            "js/plugins/tables/datatables/datatables.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/jquery.form.min.js",
            "js/plugins/forms/selects/select2.min.js",
        );
        if($genderId != '') {
            $data['getGenderData'] = $this->Mdl_gender->getGenderData($genderId);

        }

        $this->dt_ci_template->load("default", "Gender/v_gender_manage", $data);
    }



    // Save function here
    public function save()
    {
        $genderId      = $this->input->post('gender_id');
        $genderName    = $this->input->post('gender_name', TRUE);

        $this->form_validation->set_rules('gender_name', $this->lang->line('gender'), 'required');

        $this->form_validation->set_message('required', '%s is required');

        if(isset($genderId) && $genderId != '') {
            $existingGender = $this->Mdl_gender->getExistingGender($genderId);
        }
        else {
            $existingGender = $this->Mdl_gender->getExistingGender();
        }
        if(is_array($existingGender)) {
            $existingGender = array_column($existingGender,"gender_name");
        }
        if(is_array($genderName) && count($genderName) > 0) {
            foreach ($genderName as $key => $val) {
                if(in_array(strtolower($val),array_map('strtolower',$existingGender))) {
                    $response['success'] = false;
                    $response['msg'] = "Duplicate entry for ".$val;
                    echo json_encode($response);
                    exit;
                }
            }
        }

        if ($this->form_validation->run() == false) {
            $response['success'] = false;
            $response['msg'] = strip_tags(validation_errors(""));
            echo json_encode($response);
            exit;
        } else {

            $genderArray = array(
                'gender_id' => $genderId,
				'gender_name' => $genderName

            );
            $genderData = $this->Mdl_gender->insertUpdateRecord($genderArray, 'gender_id', 'tbl_gender', 1);

            if (isset($genderId) && $genderId != '') {
                if ($genderData['success']) {
                    $response['success'] = true;
                    $response['msg'] = sprintf($this->lang->line('update_record'), GENDER);
                } else {
                    $response['success'] = false;
                    $response['msg'] = sprintf($this->lang->line('update_record_error'), GENDER);
                }
            } else {
                if ($genderData['success']) {
                    $response['success'] = true;
                    $response['msg'] = sprintf($this->lang->line('create_record'), GENDER);
                } else {
                    $response['success'] = false;
                    $response['msg'] = sprintf($this->lang->line('create_record_error'), GENDER);
                }
            }

            echo json_encode($response);
        }
    }

    public function delete()
    {
        $genderId = $this->input->post('deleteId',TRUE);

        if( is_reference_in_table('gender_id', 'tbl_employee', $genderId)){

            $response['success'] = false;
            $response['msg'] = $this->lang->line('delete_record_dependency');
            echo json_encode($response);
            exit;


        }

        //delete  member
        $genderData = $this->Mdl_gender->deleteRecord($genderId);
        if ($genderData) {
            $response['success'] = true;
            $response['msg']     = sprintf($this->lang->line('delete_record'),GENDER);
        } else {
            $response['success'] = false;
            $response['msg'] = sprintf($this->lang->line('error_delete_record'),GENDER);
        }
        echo json_encode($response);
    }


    public function getGenderDD(){
        $genderId   = $this->input->post("gender_id");
        $searchTerm = $this->input->post("filter_param");

        $data = array(
            "gender_id"     => $genderId,
            "filter_param"  => $searchTerm
        );
        echo $this->Mdl_gender->getGenderDD($data);
    }

}
