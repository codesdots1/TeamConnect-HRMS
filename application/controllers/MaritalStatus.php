<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MaritalStatus extends DT_CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("Mdl_marital_status"));
        $this->lang->load('marital_status');
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

        $this->dt_ci_template->load("default", "MaritalStatus/v_marital_status", $data);
    }

    // ajax call to the data listing
    public function getMaritalStatusListing()
    {
        $this->load->library('datatables');
        $this->datatables->select("ms.marital_status_id,ms.marital_status");
        $this->datatables->from("tbl_marital_status as ms");
        echo $this->datatables->generate();

    }

    //insert and update function
    public function manage($maritalStatusId = '') // change here manage
    {

        $data['extra_js'] = array(
            "js/plugins/tables/datatables/datatables.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/jquery.form.min.js",
            "js/plugins/forms/selects/select2.min.js",
        );
        if($maritalStatusId != '') {
            $data['getMaritalStatusData'] = $this->Mdl_marital_status->getMaritalStatusData($maritalStatusId);

        }
        $this->dt_ci_template->load("default", "MaritalStatus/v_marital_status_manage", $data);
    }




    // Save function here
    public function save()
    {
        $maritalStatusId        = $this->input->post('marital_status_id');
        $maritalStatus          = $this->input->post('marital_status', TRUE);
        $userId                 = $this->session->userdata['emp_id'];

        $this->form_validation->set_rules('marital_status', $this->lang->line('marital_status'), 'required');

        $this->form_validation->set_message('required', '%s is required');

        if(isset($maritalStatusId) && $maritalStatusId != '') {
            $existingMaritalStatus = $this->Mdl_marital_status->getExistingMaritalStatus($maritalStatusId);
        }
        else {
            $existingMaritalStatus = $this->Mdl_marital_status->getExistingMaritalStatus();
        }
        if(is_array($existingMaritalStatus)) {
            $existingMaritalStatus = array_column($existingMaritalStatus,"marital_status");
        }
        if(is_array($maritalStatus) && count($maritalStatus) > 0) {
            foreach ($maritalStatus as $key => $val) {
                if(in_array(strtolower($val),array_map('strtolower',$existingMaritalStatus))) {
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

            $maritalStatusArray = array(
                'marital_status_id' => $maritalStatusId,
				'marital_status' => $maritalStatus
            );
            $maritalStatusData = $this->Mdl_marital_status->insertUpdateRecord($maritalStatusArray, 'marital_status_id', 'tbl_marital_status', 1);

            if (isset($maritalStatusId) && $maritalStatusId != '') {
                if ($maritalStatusData['success']) {
                    $response['success'] = true;
                    $response['msg'] = sprintf($this->lang->line('update_record'), MARITALSTATUS);
                } else {
                    $response['success'] = false;
                    $response['msg'] = sprintf($this->lang->line('update_record_error'), MARITALSTATUS);
                }
            } else {
                if ($maritalStatusData['success']) {
                    $response['success'] = true;
                    $response['msg'] = sprintf($this->lang->line('create_record'), MARITALSTATUS);
                } else {
                    $response['success'] = false;
                    $response['msg'] = sprintf($this->lang->line('create_record_error'), MARITALSTATUS);
                }
            }

            echo json_encode($response);
        }
    }

    public function delete()
    {
        $maritalStatusId = $this->input->post('delete_id',TRUE);

        if( is_reference_in_table('marital_status_id', 'tbl_employee', $maritalStatusId)){

            $response['success'] = false;
            $response['msg'] = $this->lang->line('delete_record_dependency');
            echo json_encode($response);
            exit;
        }

        //delete  member
        $maritalStatusData = $this->Mdl_marital_status->deleteRecord($maritalStatusId);
        if ($maritalStatusData) {
            $response['success'] = true;
            $response['msg']     = sprintf($this->lang->line('delete_record'),MARITALSTATUS);
        } else {
            $response['success'] = false;
            $response['msg'] = sprintf($this->lang->line('error_delete_record'),MARITALSTATUS);
        }
        echo json_encode($response);
    }


    public function getMaritalStatusDD(){
        $maritalStatusId = $this->input->post("marital_status_id");
        $searchTerm = $this->input->post("filter_param");

        $data = array(
            "marital_status_id"     => $maritalStatusId,
            "filter_param"          => $searchTerm
        );
        echo $this->Mdl_marital_status->getMaritalStatusDD($data);
    }

}
