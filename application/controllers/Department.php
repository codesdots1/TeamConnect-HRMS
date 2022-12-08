<?php


class Department extends DT_CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Curl');
		$this->load->model(array("Mdl_department"));
		$this->lang->load('department');
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

		$this->dt_ci_template->load("default", "department/v_department", $data);
	}

	// ajax call to the data listing
	public function getDepartmentListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("td.department_id,td.dept_name,td.description");
		$this->datatables->from("tbl_department as td");
		echo $this->datatables->generate();

	}

	//insert and update function
	public function manage($departmentId = '') // change here manage
	{

		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/forms/selects/select2.min.js",
		);
		if ($departmentId != '') {
			$data['getDepartmentData'] = $this->Mdl_department->getDepartmentData($departmentId);

		}

		$this->dt_ci_template->load("default", "department/v_department_manage", $data);
	}


	// Save function here
	public function save()
	{
		$departmentId 	= $this->input->post('department_id');
		$departmentName = $this->input->post('dept_name', TRUE);
		$description 	= $this->input->post('description', TRUE);

		if(isset($departmentId) && $departmentId == ''){
			$this->form_validation->set_rules('dept_name', $this->lang->line('department_name'), 'required|is_unique[tbl_department.dept_name]');
		} else{
			$this->form_validation->set_rules('dept_name', $this->lang->line('department_name'), 'required|edit_unique[tbl_department.dept_name.'.$departmentId.']');
		}
		$this->form_validation->set_rules('dept_name', $this->lang->line('department_name'), 'required');
		$this->form_validation->set_message('required', '%s is required');

		if (isset($departmentId) && $departmentId != '') {
			$existingDepartment = $this->Mdl_department->getExistingDepartment($departmentId);
		} else {
			$existingDepartment = $this->Mdl_department->getExistingDepartment();
		}
		if (is_array($existingDepartment)) {
			$existingDepartment = array_column($existingDepartment, "dept_name");
		}
		if (is_array($departmentName) && count($departmentName) > 0) {
			foreach ($departmentName as $key => $val) {
				if (in_array(strtolower($val), array_map('strtolower', $existingDepartment))) {
					$response['success'] = false;
					$response['msg'] = "Duplicate entry for " . $val;
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

			$departmentArray = array(
				'department_id'	 => $departmentId,
				'dept_name' 	 => $departmentName,
				'description' 	 => $description
				);

			$departmentData = $this->Mdl_department->insertUpdateRecord($departmentArray, 'department_id', 'tbl_department', 1);

			if (isset($departmentId) && $departmentId != '') {
				if ($departmentData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), DEPARTMENT);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), DEPARTMENT);
				}
			} else {
				if ($departmentData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), DEPARTMENT);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), DEPARTMENT);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$departmentId = $this->input->post('deleteId', TRUE);
		$ids = is_array($departmentId) ? implode(',',$departmentId) : $departmentId;
		  
		if (is_reference_in_table('department_id', 'tbl_employee', $departmentId)) {
			$response['success'] = false;
			$response['msg'] = $this->lang->line('delete_record_dependency');
			echo json_encode($response);
			exit;
		}

		//delete  member
		$departmentData = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/deleteDepartment', array('department_id'=>$ids));

		$departmentData = json_decode($departmentData);
		if ($departmentData->status == "true") {
			$response['success'] = true;
			$response['msg'] = sprintf($this->lang->line('delete_record'), DEPARTMENT);
		} else {
			$response['success'] = false;
			$response['msg'] = sprintf($this->lang->line('error_delete_record'), DEPARTMENT);
		}
		echo json_encode($response);
	}


	public function getDepartmentDD()
	{
		$departmentId 	= $this->input->post("department_id");
		$searchTerm 	= $this->input->post("filter_param");

		$data = array(
			"department_id" => $departmentId,
			"filter_param" => $searchTerm
		);
		echo $this->Mdl_department->getDepartmentDD($data);
	}
}
