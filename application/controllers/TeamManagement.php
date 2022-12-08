<?php

class TeamManagement extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_team_management","Mdl_employee","Mdl_project"));
		$this->lang->load('team_management');
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

		$this->dt_ci_template->load("default", "teamManagement/v_team_management", $data);
	}

	// ajax call to the data listing
	public function getTeamManagementListing()
	{
		$empId  = $this->input->post('empId');
		$role   = $this->session->userdata('role');

		$this->load->library('datatables');
		if($empId != ''){
			$this->datatables->select("tt.team_id,tt.team_name,tt.emp_id,concat(te.first_name,' ',te.last_name) as emp_head,
			(case when te.emp_id = ".$empId." then 0 else te.emp_id end) as loginEmp,
			tt.emp_id_listing,tt.project_id_listing,tt.description");
		} else {
			$this->datatables->select("tt.team_id,tt.team_name,tt.emp_id,concat(te.first_name,' ',te.last_name) as emp_head,
			tt.emp_id_listing,tt.project_id_listing,tt.description");
		}

		$this->datatables->from("tbl_employee as te");

		if($role == 'team leader'){
			$this->datatables->where('tr.role_id',$role);
		}

		if(!empty($empIds)){
			$this->datatables->where_in('te.emp_id', $empIds);
			if($empId != '' ){
				$this->db->order_by('loginEmp', 'ASC');
			}
		}

		$this->datatables->join("tbl_team as tt","tt.emp_id = te.emp_id");
		$this->datatables->join("tbl_role as tr","te.role_id = tr.role_id");
		$allData = json_decode($this->datatables->generate());
		$results = $allData->data;
		$i = 0;
		foreach($results as $result){
			$ids 		= explode(",",$result->emp_id_listing);
			$projectIds = explode(",",$result->project_id_listing);
			$result 	= (array)$result;
			$employees  = array();
			foreach($ids as $id){
				$employeeData = $this->Mdl_employee->getEmployeeEmail($id);
				array_push($employees,$employeeData['emp_name']);
			}

			$projects = array();
			foreach($projectIds as $id){
				$projectData = $this->Mdl_project->getProjectData($id);
				array_push($projects,$projectData['project_name']);
			}

			$result['team_members'] = ucwords(implode(", ",$employees));
			$result['projects'] 	= ucwords(implode(", ",$projects));
			$result 				= (object)$result;
			$results[$i] 			= $result;
			$i++;
		}

		$datatable = array(
			'draw' 				=> $allData->draw,
			'recordsTotal' 		=> $allData->recordsTotal,
			'recordsFiltered'	=> $allData->recordsFiltered,
			'data' 				=> $results
		);
		echo json_encode($datatable);
	}

	//insert and update function
	public function manage($teamId = '') // change here manage
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
			'teamMeambers'  => true,
			'project'  		=> true,
		);

		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if($teamId != '') {
			$data['getTeamData'] = $this->Mdl_team_management->getTeamManagementData($teamId);
		}

		$this->dt_ci_template->load("default", "teamManagement/v_team_management_manage", $data);
	}


	// Save function here
	public function save()
	{
		$teamId         = $this->input->post('team_id');
		$teamName       = $this->input->post('team_name');
		$empId          = $this->input->post('emp_active_id');
		$empIdLists     = $this->input->post('emplistdd[]');
		$projects       = $this->input->post('projectlistdd[]');
		$description    = $this->input->post('description');
		$empIdList 		= implode(", ",$empIdLists);
		$projects 		= implode(", ",$projects);

		if (isset($teamId) && $teamId == '') {
			$this->form_validation->set_rules('team_name', $this->lang->line('team_name'), 'required|is_unique[tbl_team.team_name]');
		} else{
			$this->form_validation->set_rules('team_name', $this->lang->line('team_name'), 'required|edit_unique[tbl_team.team_name.' .$teamId. ']');
		}

		$this->form_validation->set_rules('emp_active_id', $this->lang->line('team_head'), 'required');
		$this->form_validation->set_rules('emplistdd[]', $this->lang->line('team_members'), 'required');

		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('is_unique', 'This %s Already Exists');
		$this->form_validation->set_message('edit_unique', 'This %s Already Exists');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg']     = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$teamArray = array(
				'team_id' 		   		=>  $teamId,
				'team_name' 			=>  $teamName,
				'emp_id'	    		=> 	$empId,
				'project_id_listing'    => 	$projects,
				'emp_id_listing'    	=> 	$empIdList,
				'description'  			=> 	$description
			);
			$teamData  = $this->Mdl_team_management->insertUpdateRecord($teamArray, 'team_id', 'tbl_team', 1);


			if (isset($teamId) && $teamId != '') {
				if ($teamData['success']) {
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('update_record'), TEAM);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('update_record_error'), TEAM);
				}
			} else {
				if ($teamData['success']) {
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('create_record'), TEAM);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('create_record_error'), TEAM);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$teamId 		  = $this->input->post('deleteId',TRUE);
		$employeeRoleData = $this->Mdl_team_management->deleteRecord($teamId);

		//delete Team Management
		if ($employeeRoleData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),TEAM);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),TEAM);
		}
		echo json_encode($response);
	}
}
