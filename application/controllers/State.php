<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class State extends DT_CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("Mdl_state","Mdl_country"));
        $this->lang->load('state');
    }

    //Index page
    public function index()
    {
        $data['extra_js'] = array(
            "js/plugins/tables/datatables/datatables.min.js",
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/plugins/forms/jquery.form.min.js",
            "js/plugins/forms/selects/select2.min.js"
        );

        $data['countryData'] = $this->Mdl_country->getData();
        $data['v_stateModal'] = $this->load->view('commonMaster/v_stateModal', $data, TRUE);
        $this->dt_ci_template->load("default", "commonMaster/v_state", $data);
    }

    // ajax call to the data listing
    public function getstateListing()
    {
        $this->load->library('datatables');
        $this->datatables->select("state_id,state_name,state.country_id,country.country_name,state.is_active");
        $this->datatables->from("tbl_state as state");
        $this->datatables->join("tbl_country as country",'country.country_id = state.country_id', 'left');
        echo $this->datatables->generate();
    }

    //change status
    public function changeStatus()
    {
        $stateId    =  $this->input->post('stateId', TRUE);
        $status     =  $this->input->post('status', TRUE);

        if ($status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        $return = $this->Mdl_state->statusChange($stateId,$status,'state_id','tbl_state');
        if ($return == 1) {
            $response['success'] = true;
            $response['msg']     = sprintf($this->lang->line('status_change'),STATE);
        } else {
            $response['success'] = false;
            $response['msg']     = sprintf($this->lang->line('status_change_error'),STATE);
        }
        echo json_encode($response);
    }



    ///insert and update state
    public function save()
    {
        $stateId = $this->input->post('state_id');
        $countryId = $this->input->post('country_id');
        $stateName = $this->input->post('state_name');

        if (isset($stateId) && $stateId == '') {
            $this->form_validation->set_rules('state_name', $this->lang->line('state_name'), 'required');
        } else {
            $this->form_validation->set_rules('state_name', $this->lang->line('state_name'), 'required');
        }

        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('is_unique', 'This %s already exists');
        $this->form_validation->set_message('edit_unique', 'This %s already exists');

        if ($this->form_validation->run() == false) {
            $response['success'] = false;
            $response['msg']     = strip_tags(validation_errors(""));
            echo json_encode($response);

        } else {
            $isExist = $this->Mdl_state->checkExistState($stateId, $countryId ,$stateName);
            if($isExist == 1){
                $response['success'] = false;
                $response['msg']         = strip_tags('Duplicate State');
                echo json_encode($response);
                die();
            }

            $isActive = $this->input->post('is_active', true);
            $stateArray = array(
                'state_id'                => $stateId,
                'country_id'              => $this->input->post('country_id', TRUE),
                'state_name'              => $this->input->post('state_name', TRUE),
                'is_active'               => isset($isActive) ? 1 : 0,
            );

            $stateData = $this->Mdl_state->insertUpdateRecord($stateArray,'state_id','tbl_state');

            if (isset($stateId) && $stateId != '') {
                if ($stateData['success']) {
                    $response['success']      = true;
                    $response['msg']          = sprintf($this->lang->line('update_record'),STATE);
                } else {
                    $response['success']      = false;
                    $response['msg']          = sprintf($this->lang->line('update_record_error'),STATE);
                }
            } else {
                if ($stateData['success']) {
                    $response['success']  = true;
                    $response['msg']      = sprintf($this->lang->line('create_record'),STATE);
                } else {
                    $response['success']  = false;
                    $response['msg']      = sprintf($this->lang->line('create_record_error'),STATE);
                }
            }

            echo json_encode($response);
        }
    }


    public function delete()
    {
        $stateId = $this->input->post('deleteId',TRUE);
        $ids = is_array($stateId) ? implode(',',$stateId) : $stateId;

        if(is_reference_in_table('state_id', 'tbl_city', $stateId)
            || is_reference_in_table('state_id', 'tbl_employee', $stateId)
            || is_reference_in_table('state_id', 'tbl_companies', $stateId)){
            $response['success'] = false;

            logActivity('Tried to delete State [StateID: ' . $ids . ']',$this->data['userId'],'State');

            $response['msg'] = $this->lang->line('delete_record_dependency');
            echo json_encode($response);
            exit;
        }

        //delete  state
        $stateData = $this->Mdl_state->deleteRecord($stateId);
        if ($stateData) {
            $response['success'] = true;
            $response['msg']     = sprintf($this->lang->line('delete_record'),STATE);
        } else {
            $response['success'] = false;
            $response['msg'] =  sprintf($this->lang->line('error_delete_record'),STATE);
        }

        echo json_encode($response);
    }

    //edit time get the state data
    public function getData(){
        $stateId = $this->input->post('stateId');
        $data['get_data'] = $this->Mdl_state->getData($stateId);
        echo json_encode($data['get_data']);
    }


    public function getStateDD()
	{
		$filterParameter = $this->input->post('filter_param');
		$countryId = $this->input->post('country_id');
		$stateIdActive = $this->input->post('stateIdActive');
		$page = $this->input->post('page');

		echo $this->Mdl_state->getStateDD($filterParameter,$page,$countryId,$stateIdActive);
    }




}

?>
