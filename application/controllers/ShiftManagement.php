<?php


class ShiftManagement extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_shift_management"));
		$this->lang->load('shift_management');
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

		$this->dt_ci_template->load("default", "shiftManagement/v_shift_management", $data);
	}

	// ajax call to the data listing
	public function getShiftManagementListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("te.emp_id,concat(te.first_name,' ',te.last_name) as name,tes.shift_name");
		
		$this->datatables->from("tbl_employee as te");
		$this->datatables->join("tbl_employee_shift as tes","tes.shift_id = te.shift_id","left");
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($employeeId = '') // change here manage
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
			'employeeShift'  => true,
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);
		if($employeeId != '') {
			$data['getShiftManagementData'] = $this->Mdl_shift_management->getShiftManagementData($employeeId);

		}
		
		$this->dt_ci_template->load("default", "shiftManagement/v_shift_management_manage", $data);
	}



	// Save function here
	public function save()
	{
		$empId       = $this->input->post('emp_id');
		$shiftId     = $this->input->post('shift_id');

		$this->form_validation->set_rules('emp_id', $this->lang->line('employee'), 'required');
		$this->form_validation->set_rules('shift_id', $this->lang->line('shift'), 'required');

		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$shiftManagementArray = array(
				'emp_id'	    => $empId,
				'shift_id'  	=> $shiftId,
			);

			$shiftManagementData  = $this->Mdl_shift_management->insertUpdateRecord($shiftManagementArray, 'emp_id', 'tbl_employee', 1);
			if (isset($empId) && $empId != '') {
				if ($shiftManagementData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), SHIFTMANAGEMENT);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), SHIFTMANAGEMENT);
				}
			} else {
				if ($shiftManagementData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), SHIFTMANAGEMENT);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), SHIFTMANAGEMENT);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$shiftId = $this->input->post('deleteId',TRUE);
		$ids = is_array($shiftId) ? implode(',',$shiftId) : $shiftId;

		if( is_reference_in_table('shift_id', 'tbl_employee', $shiftId)) {
			$response['success'] = false;
			$response['msg'] = $this->lang->line('delete_record_dependency');
			echo json_encode($response);
			exit;
		}

		$employeeShiftData = $this->Mdl_shift_management->deleteRecord($shiftId);
		//delete Business Type
		if ($employeeShiftData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),SHIFTMANAGEMENT);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),SHIFTMANAGEMENT);
		}
		echo json_encode($response);
	}
	
}
