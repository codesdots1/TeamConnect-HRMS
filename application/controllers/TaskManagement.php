<?php


class TaskManagement extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_task_management","Mdl_employee","Mdl_project","Mdl_task","Mdl_team_management"));
		$this->lang->load('task_management');
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

		$this->dt_ci_template->load("default", "taskManagement/v_task_management", $data);
	}

	// ajax call to the data listing
	public function getTaskManagementListing()
	{
		$empId  = $this->input->post('empId');
		$role   = $this->session->userdata('role');

		if(strtolower($role) != 'admin'){
			$loginEmp 	=  $empId;
			$data 		= $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds 	= explode(",",$data['emp_id_listing']);
			array_push($empIds,$loginEmp);
		}

		$this->load->library('datatables');
		if($empId != ""){
			$this->datatables->select("ttm.task_manage_id,ttm.emp_id,concat(te.first_name,' ',te.last_name) as emp,
			(case when te.emp_id = ".$empId." then 0 else te.emp_id end) as loginEmp,
			ttm.task_id_listing,ttm.project_id,tp.project_name");
		} else {
			$this->datatables->select("ttm.task_manage_id,ttm.emp_id,concat(te.first_name,' ',te.last_name) as emp,
			ttm.task_id_listing,ttm.project_id,tp.project_name");
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

		$this->datatables->join("tbl_task_management as ttm","ttm.emp_id = te.emp_id");
		$this->datatables->join("tbl_project as tp","ttm.project_id = tp.project_id");
		$this->datatables->join("tbl_role as tr","te.role_id = tr.role_id");

		$allData = json_decode($this->datatables->generate());
		$results = $allData->data;
		$i 		 = 0;
		foreach($results as $result){
			$ids 	= explode(",",$result->task_id_listing);
			$result = (array)$result;
			$tasks 	= array();
			foreach($ids as $id){
				$taskData = $this->Mdl_task->getTaskData($id);
				array_push($tasks,$taskData['task_name']);
			}
			
			
			$result['tasks'] = ucwords(implode(", ",$tasks));
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
	public function manage($taskManageId = '') // change here manage
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
			'task'  		=> true,
			'teamHead'  	=> true,
			'TLMembers'  	=> true,
			'teamProject'  	=> true,
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);
		if($taskManageId != '') {
			$data['getTaskData'] = $this->Mdl_task_management->getTaskManagementData($taskManageId);

		} else {
			$empId 					= $this->session->userdata('emp_id');
			$result 				= $this->Mdl_employee->getLoginEmployee($empId);
			$data['getProjectData'] = $result;
		}
		$this->dt_ci_template->load("default", "taskManagement/v_task_management_manage", $data);
	}



	// Save function here
	public function save()
	{
		$taskManageId   = $this->input->post('task_manage_id');
		$empId          = $this->input->post('emp_active_id');
		$taskIdList     = $this->input->post('tasklistdd[]');
		$empIdList     = $this->input->post('emplistdd');
		$project        = $this->input->post('project_id');
		$taskIdList 	= implode(",",$taskIdList);

		$this->form_validation->set_rules('emp_active_id',  $this->lang->line('employee'), 'required');
		$this->form_validation->set_rules('project_id',  $this->lang->line('project'), 'required');
		$this->form_validation->set_rules('tasklistdd[]', $this->lang->line('task'), 'required');

		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('is_unique', 'This %s Already Exists');
		$this->form_validation->set_message('edit_unique', 'This %s Already Exists');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$taskManagementArray = array(
				'task_manage_id' 	    =>  $taskManageId,
				'emp_id'	    		=> 	$empId,
				'project_id'            => 	$project,
				'emp_id_listing'    	=> 	$empIdList,
				'task_id_listing'    	=> 	$taskIdList
			);
			$taskManageData  = $this->Mdl_task_management->insertUpdateRecord($taskManagementArray, 'task_manage_id', 'tbl_task_management', 1);

			if (isset($taskManageId) && $taskManageId != '') {
				if ($taskManageData['success']) {
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('update_record'), TASK);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('update_record_error'), TASK);
				}
			} else {
				if ($taskManageData['success']) {
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('create_record'), TASK);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('create_record_error'), TASK);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$taskManageId = $this->input->post('deleteId',TRUE);
		$taskData 	  = $this->Mdl_task_management->deleteRecord($taskManageId);
		//delete Project Team
		if ($taskData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),TASK);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),TASK);
		}
		echo json_encode($response);
	}
}
