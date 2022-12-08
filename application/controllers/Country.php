<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Country extends DT_CI_Controller {

    public function __construct()
    {
        parent::__construct();
		$this->load->library('Curl');
        $this->load->model("Mdl_country");
        $this->lang->load('country');
    }

    //Index page
    public function index()
    {
        $data['extra_js'] = array(
            "js/plugins/tables/datatables/datatables.min.js",
            "js/plugins/notifications/sweet_alert.min.js",
            "js/plugins/forms/styling/uniform.min.js",
            "js/plugins/forms/jquery.form.min.js",
        );

        $data['v_countryModal'] = $this->load->view('commonMaster/v_countryModal', '', TRUE);
        $this->dt_ci_template->load("default", "commonMaster/v_country", $data);
    }


	public function getCountryListing()
	{
		$this->load->library('datatables');
		$response = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/getCountryListing', $_POST);
		$response = json_decode($response,1);
		$output = array(
			"draw" 			  => intval($_POST["draw"]),
			"recordsTotal" 	  => $this->Mdl_country->getAllData(),
			"recordsFiltered" => $this->Mdl_country->getFilteredData(),
			"data" => $response['data']
		);
		echo json_encode($output);
	}

    ///insert and update country
    public function save()
    {
        $countryId   = $this->input->post('country_id');
        $countryName = $this->input->post('country_name');
		$isActive    = $this->input->post('is_active', true)? '1' : '0';
        if (isset($countryId) && $countryId == '') {
            $this->form_validation->set_rules('country_name', $this->lang->line('country_name'), 'required|is_unique[tbl_country.country_name]');
        } else {
            $this->form_validation->set_rules('country_name', $this->lang->line('country_name'), 'required|edit_unique[tbl_country.country_name.' . $countryId . ']');
        }

        $this->form_validation->set_message('required', '%s is required');
        $this->form_validation->set_message('is_unique', 'This %s already exists');
        $this->form_validation->set_message('edit_unique', 'This %s already exists');


        if ($this->form_validation->run() == false) {
            $response['success'] = false;
            $response['msg']     = strip_tags(validation_errors(""));
            echo json_encode($response);

        } else {
			$countryArray =	array(
						'country_id'	 => $countryId,
						'country_name' 	 => $countryName,
						'is_active' 	 => $isActive
					);
			$countryData = $this->Mdl_country->insertUpdateRecord($countryArray, 'country_id', 'tbl_country', 1);

			if (isset($countryId) && $countryId != '') {
				if ($countryData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), COUNTRY);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), COUNTRY);
				}
			} else {
				if ($countryData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), COUNTRY);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), COUNTRY);
				}
			}
			echo json_encode($response);
		}
    }

    //change status
    public function changeStatus()
    {
        $countryId  =  $this->input->post('countryId', TRUE);
        $status     =  $this->input->post('status', TRUE);

        if ($status == 0) {
            $status = 1;
        } else {
            $status = 0;
        }

        $return = $this->Mdl_country->statusChange($countryId,$status,'country_id','tbl_country');
        if ($return == 1) {
            $response['success'] = true;
            $response['msg']     = sprintf($this->lang->line('status_change'),COUNTRY);
        } else {
            $response['success'] = false;
            $response['msg']     = sprintf($this->lang->line('status_change_error'),COUNTRY);
        }
        echo json_encode($response);
    }

    public function delete()
    {
        $countryId = $this->input->post('deleteId',TRUE);
        $ids = is_array($countryId) ? implode(',',$countryId) : $countryId;

        if( is_reference_in_table('country_id', 'tbl_state', $countryId)
			|| is_reference_in_table('country_id', 'tbl_employee', $countryId)
			|| is_reference_in_table('country_id', 'tbl_companies', $countryId)){
            $response['success'] = false;
            logActivity('Tried to delete Country [CountryID: ' . $ids . ']',$this->data['userId'],'Country');
            $response['msg'] = $this->lang->line('delete_record_dependency');
            echo json_encode($response);
            exit;
        }
		 
        //delete  country
	    $countryData = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/deleteCountry', array('country_id'=>$ids));
		$countryData = json_decode($countryData);

        if ($countryData->status == "true") {
            $response['success'] = true;
            $response['msg']     = sprintf($this->lang->line('delete_record'),COUNTRY);
        } else {
            $response['success'] = false;
            $response['msg'] =  sprintf($this->lang->line('error_delete_record'),COUNTRY);
        }

        echo json_encode($response);
    }



    //edit time get the country data
    public function getData()
	{
        $countryId = $this->input->post('countryId');
        $data['get_data'] = $this->Mdl_country->getData($countryId);
        echo json_encode($data['get_data']);
    }


    public function getCountryDD()
	{
        $filterParameter = $this->input->post('filter_param');
        $countryIdActive = $this->input->post('countryIdActive');
        $page = $this->input->post('page');
        echo $this->Mdl_country->getCountryDD($filterParameter,$page,$countryIdActive);
    }

}

?>
