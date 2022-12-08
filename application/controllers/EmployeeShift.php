<?php


class EmployeeShift extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_employee_shift"));
		$this->lang->load('employee_shift');
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

		$this->dt_ci_template->load("default", "employeeShift/v_employee_shift", $data);
	}

	// ajax call to the data listing
	public function getEmployeeShiftListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("tes.shift_id,tes.shift_name,tes.start_time,
        tes.end_time,tes.total_hours,tes.is_active");
		$this->datatables->from("tbl_employee_shift as tes");
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($shiftId = '') // change here manage
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
		if($shiftId != '') {
			$data['getEmployeeShiftData'] = $this->Mdl_employee_shift->getEmployeeShiftData($shiftId);

		}

		$this->dt_ci_template->load("default", "employeeShift/v_employee_shift_manage", $data);
	}



	// Save function here
	public function save()
	{
		$shiftId     = $this->input->post('shift_id');
		$shiftName   = $this->input->post('shift_name', TRUE);
		$startTime   = $this->input->post('start_time', TRUE);
		$endTime     = $this->input->post('end_time', TRUE);
		$totalHours  = $this->input->post('total_hours', TRUE);
		$isActive    = $this->input->post('is_active', TRUE);

		$this->form_validation->set_rules('shift_name', $this->lang->line('shift_name'), 'required');
		$this->form_validation->set_rules('start_time', $this->lang->line('start_time'), 'required');
		$this->form_validation->set_rules('end_time', $this->lang->line('end_time'), 'required');
		$this->form_validation->set_rules('total_hours', $this->lang->line('total_hours'), 'required');

		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$employeeShiftArray = array(
				'shift_id'	    => $shiftId,
				'shift_name'  	=> $shiftName,
				'start_time'  	=> $startTime,
				'end_time'      => $endTime,
				'total_hours'   => $totalHours,
				'is_active'     => isset($isActive) ? 1 : 0,
			);

			$employeeShiftData  = $this->Mdl_employee_shift->insertUpdateRecord($employeeShiftArray, 'shift_id', 'tbl_employee_shift', 1);

			if (isset($shiftId) && $shiftId != '') {
				if ($employeeShiftData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), EMPLOYEESHIFT);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), EMPLOYEESHIFT);
				}
			} else {
				if ($employeeShiftData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), EMPLOYEESHIFT);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), EMPLOYEESHIFT);
				}
			}
			echo json_encode($response);
		}
	}

	public function changeActive()
	{
		$shiftId   = $this->input->post('shift_id', TRUE);
		$status    = $this->input->post('status', TRUE);
		if ($status == 0) {
			$status = 1;
		} else {
			$status = 0;
		}
		$return = $this->Mdl_employee_shift->statusChange($shiftId, $status, 'shift_id', 'tbl_employee_shift');
		if ($return == 1)
		{
			$response['success']  = true;
			$response['msg']      = sprintf($this->lang->line('status_change'), EMPLOYEESHIFT);
		}
		else
		{
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('status_change_error'), EMPLOYEESHIFT);
		}
		echo json_encode($response);

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

		$employeeShiftData = $this->Mdl_employee_shift->deleteRecord($shiftId);
		//delete Business Type
		if ($employeeShiftData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),EMPLOYEESHIFT);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),EMPLOYEESHIFT);
		}
		echo json_encode($response);
	}


	public function getEmployeeShiftDD()
	{
		$shiftId     = $this->input->post("shift_id");
		$searchTerm  = $this->input->post("filter_param");

		$data = array(
			"shift_id"      => $shiftId,
			"filter_param"  => $searchTerm
		);
		echo $this->Mdl_employee_shift->getEmployeeShiftDD($data);
	}
}
