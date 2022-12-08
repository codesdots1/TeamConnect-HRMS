<?php


class LeaveReason extends DT_CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("Mdl_leave_reason");
		$this->lang->load('leave_reason');
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

		$data['v_leave_reasonModal'] = $this->load->view('leaveReason/v_leave_reasonModal', '', TRUE);
		$this->dt_ci_template->load("default", "leaveReason/v_leave_reason", $data);
	}


	public function getLeaveReasonListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("tlr.leave_reason_id,tlr.leave_reason_name");
		$this->datatables->from("tbl_leave_reason as tlr");
		echo $this->datatables->generate();
	}

	public function save()
	{
		$leaveReasonId   = $this->input->post('leave_reason_id',TRUE);
		$leaveReasonName = $this->input->post('leave_reason_name',TRUE);

		if (isset($leaveReasonId) && $leaveReasonId == '') {
			$this->form_validation->set_rules('leave_reason_name', $this->lang->line('leave_reason_name'), 'required|is_unique[tbl_leave_reason.leave_reason_name]');
		} else {
			$this->form_validation->set_rules('leave_reason_name', $this->lang->line('leave_reason_name'), 'required|edit_unique[tbl_leave_reason.leave_reason_name.' . $leaveReasonId . ']');
		}

		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('is_unique', 'This %s already exists');
		$this->form_validation->set_message('edit_unique', 'This %s already exists');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg']     = strip_tags(validation_errors(""));
			echo json_encode($response);

		} else {
			$leaveReasonArray =	array(
				'leave_reason_id'	 => $leaveReasonId,
				'leave_reason_name'  => $leaveReasonName,
			);

			$leaveReasonData = $this->Mdl_leave_reason->insertUpdateRecord($leaveReasonArray, 'leave_reason_id', 'tbl_leave_reason', 1);

			if (isset($leaveReasonId) && $leaveReasonId != '') {
				if ($leaveReasonData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), LEAVEREASON);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), LEAVEREASON);
				}
			} else {
				if ($leaveReasonData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), LEAVEREASON);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), LEAVEREASON);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$leaveReasonId     = $this->input->post('deleteId',TRUE);
		$leaveReasonData   = $this->Mdl_leave_reason->deleteRecord($leaveReasonId);

		if ($leaveReasonData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),LEAVEREASON);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),LEAVEREASON);
		}
		echo json_encode($response);
	}

	public function getData()
	{
		$leaveReasonId 	  = $this->input->post('leaveReasonId');
		$data['get_data'] = $this->Mdl_leave_reason->getData($leaveReasonId);
		echo json_encode($data['get_data']);
	}

	public function getLeaveReasonDD()
	{
		$leaveReasonId = $this->input->post("leave_reason_id");
		$searchTerm    = $this->input->post("filter_param");

		$data = array(
			"leave_reason_id" => $leaveReasonId,
			"filter_param"    => $searchTerm
		);
		echo $this->Mdl_leave_reason->getLeaveReasonDD($data);
	}

}
