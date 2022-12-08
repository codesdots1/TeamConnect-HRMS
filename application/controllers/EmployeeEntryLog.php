<?php


class EmployeeEntryLog extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_employee_entry_log"));
		$this->lang->load('employee_entry_log');
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

		$this->dt_ci_template->load("default", "employeeEntryLog/v_employee_entry_log", $data);
	}

	// ajax call to the data listing
	public function getEmployeeEntryLogListing()
	{
		$startDate = $this->input->post('startDate',TRUE);
		$endDate = $this->input->post('endDate',TRUE);

		$this->load->library('datatables');
		$this->datatables->select("tel.emp_log_id,CONCAT(te.first_name,' ',te.last_name) as emp_name,tel.logout_time as out_time,
		date_format(tel.login_time,'".PHP_TIME_MYSQL_FORMAT."') as login_time,
        date_format(tel.logout_time,'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
        date_format(tel.login_date,'".DATE_FORMATE_MYSQL."') as login_date");
		$this->datatables->from("tbl_employee_log as tel");
		$this->datatables->where('LOWER(tr.role) !=', 'admin' );
		$this->datatables->join("tbl_employee as te","te.emp_id = tel.emp_id");
		$this->datatables->join('tbl_role as tr','te.role_id = tr.role_id');

		if($startDate != ''){
			$this->datatables->where('tel.login_date >=', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->datatables->where('tel.login_date <=', DMYToYMD($endDate));
		}

		echo $this->datatables->generate();
	}

	public function delete()
	{
		$employeeLogId = $this->input->post('deleteId',TRUE);

		$employeeEntryLogData = $this->Mdl_employee_entry_log->deleteRecord($employeeLogId);
		//delete Business Type
		if ($employeeEntryLogData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),EMPLOYEEENTRYLOG);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),EMPLOYEEENTRYLOG);
		}
		echo json_encode($response);
	}
}
