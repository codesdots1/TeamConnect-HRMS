<?php


class Project extends DT_CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_project"));
		$this->lang->load('project');
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

		$this->dt_ci_template->load("default", "project/v_project", $data);
	}

	// ajax call to the data listing
	public function getProjectListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("tp.project_id,tp.project_name,tp.description,tp.is_active");
		$this->datatables->from("tbl_project as tp");
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($projectId = '') // change here manage
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
		if($projectId != '') {
			$data['getProjectData'] = $this->Mdl_project->getProjectData($projectId);

		}

		$this->dt_ci_template->load("default", "project/v_project_manage", $data);
	}

	public function getProjectDataListing($projectId = '') // change here manage
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
		if($projectId != '') {
			$data['getProjectData'] = $this->Mdl_project->getProjectInfoData($projectId);

		}

		$this->dt_ci_template->load("default", "project/v_project_data", $data);
	}



	// Save function here
	public function save()
	{
		$projectId     = $this->input->post('project_id');
		$projectName   = $this->input->post('project_name', TRUE);
		$description   = $this->input->post('description', TRUE);
		$isActive      = $this->input->post('is_active', TRUE);

		$this->form_validation->set_rules('project_name', $this->lang->line('project_name'), 'required');
//		$this->form_validation->set_rules('description', $this->lang->line('start_time'), 'required');
		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$projectArray = array(
				'project_id'	    => $projectId,
				'project_name'  	=> $projectName,
				'description'  		=> $description,
				'is_active'     	=> isset($isActive) ? 1 : 0,
			);

			$projectData  = $this->Mdl_project->insertUpdateRecord($projectArray, 'project_id', 'tbl_project', 1);

			if (isset($projectId) && $projectId != '') {
				if ($projectData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), PROJECT);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), PROJECT);
				}
			} else {
				if ($projectData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), PROJECT);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), PROJECT);
				}
			}
			echo json_encode($response);
		}
	}

	public function changeActive()
	{
		$projectId   = $this->input->post('project_id', TRUE);
		$status     = $this->input->post('status', TRUE);
		if ($status == 0) {
			$status = 1;
		} else {
			$status = 0;
		}
		$return = $this->Mdl_project->statusChange($projectId, $status, 'project_id', 'tbl_project');
		if ($return == 1)
		{
			$response['success']  = true;
			$response['msg']      = sprintf($this->lang->line('status_change'), PROJECT);
		}
		else
		{
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('status_change_error'), PROJECT);
		}
		echo json_encode($response);

	}

	public function delete()
	{
		$projectId = $this->input->post('deleteId',TRUE);
		$projectData = $this->Mdl_project->deleteRecord($projectId);
		if ($projectData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),PROJECT);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),PROJECT);
		}
		echo json_encode($response);
	}

	public function getProjectDD()
	{
		$projectId     = $this->input->post("project_id");
		$searchTerm  = $this->input->post("filter_param");
		$allProject  = $this->input->post("all_project");

		$data = array(
			"project_id"      => $projectId,
			"filter_param"    => $searchTerm,
			"all_project"     => $allProject,
			
		);
		echo $this->Mdl_project->getProjectDD($data);
	}
	public function getTeamProjectDD()
	{
		$projectId     = $this->input->post("project_id");
		$searchTerm  = $this->input->post("filter_param");
		$allProject  = $this->input->post("all_project");
		$projectHead  = $this->input->post("project_head");

		$data = array(
			"project_id"      => $projectId,
			"filter_param"    => $searchTerm,
			"all_project"     => $allProject,
			"project_head"    => $projectHead,
			
		);
		echo $this->Mdl_project->getTeamProjectDD($data);
	}

}
