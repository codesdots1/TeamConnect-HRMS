<?php


class Task extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_task"));
		$this->lang->load('task');
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

		$this->dt_ci_template->load("default", "task/v_task", $data);
	}

	// ajax call to the data listing
	public function getTaskListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("tt.task_id,tt.task_name,tp.project_id,tp.project_name,tt.description,tt.is_active");
		$this->datatables->from("tbl_task as tt");
		$this->datatables->join("tbl_project as tp","tt.project_id = tp.project_id","left");
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($taskId = '') // change here manage
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
			'project'  =>true
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if($taskId != '') {
			$data['getTaskData'] = $this->Mdl_task->getTaskData($taskId);
		}

		$this->dt_ci_template->load("default", "task/v_task_manage", $data);
	}

	public function getTaskDataListing($taskId = '') // change here manage
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
		if($taskId != '') {
			$data['getTaskInfoData'] = $this->Mdl_task->getTaskInfoData($taskId);

		}

		$this->dt_ci_template->load("default", "task/v_task_data", $data);
	}



	// Save function here
	public function save()
	{
		$taskId        = $this->input->post('task_id');
		$projectId     = $this->input->post('project_id');
		$taskName      = $this->input->post('task_name', TRUE);
		$description   = $this->input->post('description', TRUE);
		$isActive      = $this->input->post('is_active', TRUE);

		$this->form_validation->set_rules('project_id', $this->lang->line('project_name'), 'required');
		$this->form_validation->set_rules('task_name', $this->lang->line('task_name'), 'required');
		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$taskArray = array(
				'task_id'	        => $taskId,
				'project_id'	    => $projectId,
				'task_name'  		=> $taskName,
				'description'  		=> $description,
				'is_active'     	=> isset($isActive) ? 1 : 0,
			);

			$taskData  = $this->Mdl_task->insertUpdateRecord($taskArray, 'task_id', 'tbl_task', 1);

			if (isset($taskId) && $taskId != '') {
				if ($taskData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), TASK);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), TASK);
				}
			} else {
				if ($taskData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), TASK);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), TASK);
				}
			}
			echo json_encode($response);
		}
	}

	public function changeActive()
	{
		$taskId   = $this->input->post('task_id', TRUE);
		$status     = $this->input->post('status', TRUE);
		if ($status == 0) {
			$status = 1;
		} else {
			$status = 0;
		}
		$return = $this->Mdl_task->statusChange($taskId, $status, 'task_id', 'tbl_task');
		if ($return == 1)
		{
			$response['success']  = true;
			$response['msg']      = sprintf($this->lang->line('status_change'), TASK);
		}
		else
		{
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('status_change_error'), TASK);
		}
		echo json_encode($response);

	}

	public function delete()
	{
		$taskId = $this->input->post('deleteId',TRUE);
		$projectData = $this->Mdl_task->deleteRecord($taskId);
		if ($projectData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),TASK);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),TASK);
		}
		echo json_encode($response);
	}

	public function getTaskDD()
	{
		$taskId     = $this->input->post("task_id");
		$projectId = $this->input->post('project_id');
		$allTask    = $this->input->post("all_task");
		$searchTerm    = $this->input->post("filter_param");

		$data = array(
			"task_id"      => $taskId,
			"project_id"      => $projectId,
			"filter_param"  => $searchTerm,
			"all_task"  => $allTask
		);
		
		echo $this->Mdl_task->getTaskDD($data);
	}
}
