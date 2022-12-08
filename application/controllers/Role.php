<?php
class Role extends DT_CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Curl');
		$this->load->model(array("Mdl_role"));
		$this->lang->load('role');
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

		$this->dt_ci_template->load("default", "role/v_role", $data);
	}

	// ajax call to the data listing
	public function getRoleListing()
	{
//		$this->load->library('datatables');
//		$response = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/getRoleListing', $_POST);
//		$response = json_decode($response,1);
//		$output = array(
//			"draw" 			  => intval($_POST["draw"]),
//			"recordsTotal" 	  => $this->Mdl_role->getAllData(),
//			"recordsFiltered" => $this->Mdl_role->getFilteredData(),
//			"data" => $response['data']
//		);
//		echo json_encode($output);

		$this->load->library('datatables');
		$this->datatables->select("tr.role_id,tr.title,tr.role,tr.description");
		$this->datatables->from("tbl_role as tr");
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($roleId = '') // change here manage
	{

		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/forms/selects/select2.min.js",
		);
		if ($roleId != '') {
			$data['getRoleData'] = $this->Mdl_role->getRoleData($roleId);

		}

		$this->dt_ci_template->load("default", "role/v_role_manage", $data);
	}


	// Save function here
	public function save()
	{
		$roleId 	 = $this->input->post('role_id');
		$title 		 = $this->input->post('title', TRUE);
		$role 		 = $this->input->post('role', TRUE);
		$description = $this->input->post('description', TRUE);

		if (isset($roleId) && $roleId == '') {
			$this->form_validation->set_rules('role', $this->lang->line('role'), 'required|is_unique[tbl_role.role]');
		} else {
			$this->form_validation->set_rules('role', $this->lang->line('role'), 'required|edit_unique[tbl_role.role.' . $roleId . ']');
		}
		$this->form_validation->set_rules('role', $this->lang->line('role'), 'required');
		$this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
		$this->form_validation->set_message('required', '%s is required');

		if (isset($roleId) && $roleId != '') {
			$existingRole = $this->Mdl_role->getExistingRole($roleId);
		} else {
			$existingRole = $this->Mdl_role->getExistingRole();
		}
		if (is_array($existingRole)) {
			$existingRole = array_column($existingRole, "role");
		}
		if (is_array($role) && count($role) > 0) {
			foreach ($role as $key => $val) {
				if (in_array(strtolower($val), array_map('strtolower', $existingRole))) {
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
			$roleArray = array(
				'role_id'		=> $roleId,
				'title' 		=> $title,
				'role' 	 	 	=> $role,
				'description' 	=> $description
				);
			$roleData = $this->Mdl_role->insertUpdateRecord($roleArray, 'role_id', 'tbl_role', 1);

			if (isset($roleId) && $roleId != '') {
				if ($roleData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), ROLE);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), ROLE);
				}
			} else {
				if ($roleData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), ROLE);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), ROLE);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$roleId = $this->input->post('deleteId', TRUE);
		$ids = is_array($roleId) ? implode(',',$roleId) : $roleId;
		
		if (is_reference_in_table('role_id', 'tbl_employee', $roleId)) {

			$response['success'] = false;
			$response['msg'] = $this->lang->line('delete_record_dependency');
			echo json_encode($response);
			exit;
		}

		//delete  member
		//$roleData = $this->curl->simple_post('http://alitainfotech.com/Employee_Management/Api/EmployeeManage/deleteRole', array('role_id'=>$ids));
		$roleData = $this->curl->simple_post('http://alitainfotech.com/sites/hrms/Api/EmployeeManage/deleteRole', array('role_id'=>$ids));
		$roleData = json_decode($roleData);

		if ($roleData->status == "true") {
			$response['success'] = true;
			$response['msg'] = sprintf($this->lang->line('delete_record'), ROLE);
		} else {
			$response['success'] = false;
			$response['msg'] = sprintf($this->lang->line('error_delete_record'), ROLE);
		}
		echo json_encode($response);
	}


	public function getRoleDD()
	{
		$roleId 	= $this->input->post("role_id");
		$searchTerm = $this->input->post("filter_param");

		$data = array(
			"role_id" => $roleId,
			"filter_param" => $searchTerm
		);
		echo $this->Mdl_role->getRoleDD($data);
	}
}
