<?php

class ProjectManagement extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_project_management","Mdl_employee","Mdl_project","Mdl_team_management"));
		$this->lang->load('project_management');
	}

	//Index page
	public function index($empId = '')
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js"
		);

		if($empId != ""){
			$data['empId'] = $empId;
		}

		$this->dt_ci_template->load("default", "projectManagement/v_project_management", $data);
	}

	// ajax call to the data listing
	public function getProjectManagementListing()
	{
		$empId  = $this->input->post('empId');
		$role   = $this->session->userdata('role');

		if(strtolower($role) != 'admin'){
			$loginEmp =  $empId;
			$data 	  = $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds   = explode(",",$data['emp_id_listing']);
			array_push($empIds,$loginEmp);
		}

		$this->load->library('datatables');

		if($empId != ""){
			$this->datatables->select("tpm.project_manage_id,tpm.team_name,tpm.emp_id,concat(te.first_name,' ',te.last_name) as emp_head,
			(case when te.emp_id = ".$empId." then 0 else te.emp_id end) as loginEmp,
			tpm.emp_id_listing,tpm.project_id,tp.project_name,tpm.description");
		} else {
			$this->datatables->select("tpm.project_manage_id,tpm.team_name,tpm.emp_id,concat(te.first_name,' ',te.last_name) as emp_head,
			tpm.emp_id_listing,tpm.project_id,tp.project_name,tpm.description");
		}
		$this->datatables->from("tbl_employee as te");

		if($role == 'team leader'){
			$this->datatables->where('tr.role_id',$role);
		}

		if(!empty($empIds)){
			$this->datatables->where_in('te.emp_id', $empIds );
			if($empId != '' ){
				$this->db->order_by('loginEmp', 'ASC');
			}
		}

		$this->datatables->join("tbl_project_management as tpm","tpm.emp_id = te.emp_id");
		$this->datatables->join("tbl_project as tp","tpm.project_id = tp.project_id");
		$this->datatables->join("tbl_role as tr","te.role_id = tr.role_id");

		$allData = json_decode($this->datatables->generate());
		$results = $allData->data;
		$i 		 = 0;
		foreach($results as $result) {
			$ids 		= explode(",",$result->emp_id_listing);
			$result 	= (array)$result;
			$employees 	= array();

			foreach($ids as $id){
				$employeeData = $this->Mdl_employee->getEmployeeEmail($id);
				array_push($employees,$employeeData['emp_name']);
			}

			$result['team_members'] = ucwords(implode(",",$employees));
			$result 	 = (object)$result;
			$results[$i] =  $result;
			$i++;
		}

		$datatable = array(
			'draw' 				=> 	$allData->draw,
			'recordsTotal' 		=> 	$allData->recordsTotal,
			'recordsFiltered'	=>	$allData->recordsFiltered,
			'data' 				=> 	$results
		);

		echo json_encode($datatable);
	}

	//insert and update function
	public function manage($projectManageId = '') // change here manage
	{

		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/media/fancybox.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/core/libraries/jquery_ui/widgets.min.js",
			"/js/plugins/pickers/anytime.min.js",
		);
		$select2 = array(
			'teamHead'  	=> true,
			'TLMembers'   	=> true,
			'teamProject'  	=> true,
		);

		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if($projectManageId != '') {
			$data['getProjectData'] = $this->Mdl_project_management->getProjectManagementData($projectManageId);
			$teamHeadId = $data['getProjectData']['team_head_id'];
//			printArray($teamHeadId,1);
		} else {
			$empId  				= $this->session->userdata('emp_id');
			$result 				= $this->Mdl_employee->getLoginEmployee($empId);
			$data['getProjectData'] = $result;
		}

		$this->dt_ci_template->load("default", "projectManagement/v_project_management_manage", $data);
	}


	// Save function here
	public function save()
	{
		$projectManageId         = $this->input->post('project_manage_id');
		$teamName       		 = $this->input->post('team_name');
		$empId          		 = $this->input->post('emp_active_id');
		$empIdList      		 = $this->input->post('emplistdd[]');
		$project       			 = $this->input->post('projectlistdd');
		$description    		 = $this->input->post('description');
		$empIdList 				 = implode(", ",$empIdList);

		if (isset($projectManageId) && $projectManageId == '') {
			$this->form_validation->set_rules('team_name', $this->lang->line('team_name'), 'required|is_unique[tbl_project_management.team_name]');
		}else{
			$this->form_validation->set_rules('team_name', $this->lang->line('team_name'), 'required|edit_unique[tbl_project_management.team_name.' .$projectManageId. ']');
		}

		$this->form_validation->set_rules('emp_active_id',  $this->lang->line('team_head'), 'required');
		$this->form_validation->set_rules('emplistdd[]', $this->lang->line('team_members'), 'required');

		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('is_unique', 'This %s Already Exists');
		$this->form_validation->set_message('edit_unique', 'This %s Already Exists');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] 	 = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$projectManagementArray = array(
				'project_manage_id'     =>  $projectManageId,
				'team_name' 			=>  $teamName,
				'emp_id'	    		=> 	$empId,
				'project_id'            => 	$project,
				'emp_id_listing'    	=> 	$empIdList,
				'description'  			=> 	$description
			);
			$projectManageData  = $this->Mdl_project_management->insertUpdateRecord($projectManagementArray, 'project_manage_id', 'tbl_project_management', 1);

			if (isset($projectManageId) && $projectManageId != '') {
				if ($projectManageData['success']) {
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('update_record'), PROJECTMANAGEMENT);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('update_record_error'), PROJECTMANAGEMENT);
				}
			} else {
				if ($projectManageData['success']) {
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('create_record'), PROJECTMANAGEMENT);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('create_record_error'), PROJECTMANAGEMENT);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$projectManageId  = $this->input->post('deleteId',TRUE);
		$employeeRoleData = $this->Mdl_project_management->deleteRecord($projectManageId);

		//delete Project Team
		if ($employeeRoleData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),PROJECTMANAGEMENT);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),PROJECTMANAGEMENT);
		}
		echo json_encode($response);
	}
}
