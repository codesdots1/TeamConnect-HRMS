<?php

class AccountDetails extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Mdl_account_details");
		$this->lang->load('account_details');
	}

	public function index(){
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/forms/selects/select2.min.js",
		);

		$this->dt_ci_template->load("default", "accountDetails/v_account_details", $data);
	}

	public function getAccountDetailsListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("tad.account_details_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name,tad.bank_name,tad.holder_name");
		$this->datatables->from("tbl_account_details as tad");
		$this->datatables->join("tbl_employee as te","te.emp_id = tad.emp_id","left");
		echo $this->datatables->generate();
	}

	public function manage($accountDetailsId = '') // change here manage
	{

		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/media/fancybox.min.js",
			"js/additional-methods.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/core/libraries/jquery_ui/widgets.min.js",
			"/js/plugins/pickers/anytime.min.js",
		);
		$select2 = array(
			'employeeName'   => true,
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);
		if($accountDetailsId != '') {
			$data['accountDetailsData'] = $this->Mdl_account_details->getAccountDetailsData($accountDetailsId);

		}

		$this->dt_ci_template->load("default", "accountDetails/v_account_details_manage", $data);
	}

	public function getAccountDetailsDataListing($accountDetailsId = '')
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/media/fancybox.min.js",
			"js/additional-methods.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/core/libraries/jquery_ui/widgets.min.js",
			"/js/plugins/pickers/anytime.min.js",
			"/js/maps/jquery.geocomplete.js",
		);
		if($accountDetailsId != '') {
			$data['getAccountDetailsInfoData']   = $this->Mdl_account_details->getAccountDetailsInfoData($accountDetailsId);

		}
		$this->dt_ci_template->load("default", "accountDetails/v_account_details_data",$data);
	}

	public function save()
	{
		$accountDetailsId         = $this->input->post('account_details_id',TRUE);
		$empId         			  = $this->input->post('emp_id',TRUE);
		$bankName                 = $this->input->post('bank_name', TRUE);
		$holderName           	  = $this->input->post('holder_name', TRUE);
		$bankCode           	  = $this->input->post('bank_code', TRUE);
		$accountNumber            = $this->input->post('account_number', TRUE);

		if(isset($accountDetailsId) && $accountDetailsId == ''){
			$this->form_validation->set_rules('bank_name', $this->lang->line('bank_name'), 'required[tbl_account_details.bank_name]');
		} else{
			$this->form_validation->set_rules('bank_name', $this->lang->line('bank_name'), 'required[tbl_account_details.bank_name.'.$accountDetailsId.']');
		}

		$this->form_validation->set_rules('holder_name', $this->lang->line('holder_name'), 'required');
		$this->form_validation->set_rules('emp_id', $this->lang->line('employee'), 'required');
		$this->form_validation->set_rules('account_number', $this->lang->line('account_number'), 'required|numeric');
		$this->form_validation->set_rules('bank_code', $this->lang->line('bank_code'), 'required');

		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('numeric', '%s Please Enter Only Number');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$accountArray = array(
				'account_details_id'           => $accountDetailsId,
				'emp_id'           			   => $empId,
				'bank_name'                    => $bankName,
				'holder_name'                  => $holderName,
				'bank_code'                    => $bankCode,
				'account_number'               => $accountNumber
			);

			$accountDetailsData  = $this->Mdl_account_details->insertUpdateRecord($accountArray, 'account_details_id', 'tbl_account_details', 1);

			if (isset($accountDetailsId) && $accountDetailsId != '') {
				if ($accountDetailsData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), ACCOUNTDETAILS);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), ACCOUNTDETAILS);
				}
			} else {
				if ($accountDetailsData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), ACCOUNTDETAILS);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), ACCOUNTDETAILS);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$accountDetailsId = $this->input->post('deleteId',TRUE);
		$accountDetailsData = $this->Mdl_account_details->deleteRecord($accountDetailsId);
		//delete Business Type
		if ($accountDetailsData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),ACCOUNTDETAILS);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),ACCOUNTDETAILS);
		}
		echo json_encode($response);
	}
}
