<?php


class WorkWeek extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_work_week"));
		$this->lang->load('work_week');
		$this->load->library('Curl');
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

		$this->dt_ci_template->load("default", "workWeek/v_work_week", $data);
	}

	// ajax call to the data listing
	public function getWorkWeekListing()
	{
//		$this->load->library('datatables');
//		$response = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/getWorkWeekListing', $_POST);
//
//		$response = json_decode($response,1);
//		$output = array(
//			"draw" 			  => intval($_POST["draw"]),
//			"recordsTotal" 	  => $this->Mdl_work_week->getAllData(),
//			"recordsFiltered" => $this->Mdl_work_week->getFilteredData(),
//			"data" => $response['data']
//		);
//		echo json_encode($output);

		$this->load->library('datatables');
		$this->datatables->select("tww.work_week_id,tww.title,tww.days_per_week,tww.days_name");
		$this->datatables->from("tbl_work_weeks as tww");
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($workWeekId = '') // change here manage
	{

		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/forms/selects/select2.min.js",
		);
		if($workWeekId != '') {
			$data['getWorkWeekData'] = $this->Mdl_work_week->getWorkWeekData($workWeekId);

		}

		$this->dt_ci_template->load("default", "workWeek/v_work_week_manage", $data);
	}



	// Save function here
	public function save()
	{
//		printArray($_POST['days_name'],1);

		$workWeekId     = $this->input->post('work_week_id');
		$title    		= $this->input->post('title', TRUE);
		$daysPerWeek    = $this->input->post('days_per_week', TRUE);
		$daysName    	= $this->input->post('days_name', TRUE);
		$daysNameString = implode(',', $daysName);
		//$userId         = $this->session->userdata['emp_id'];
		
		$this->form_validation->set_rules('title', $this->lang->line('title'), 'required');
		$this->form_validation->set_rules('days_per_week', $this->lang->line('days_per_week'), 'required');

		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$workWeekArray = array(
				'work_week_id'	    => $workWeekId,
				'title'  	   		=> $title,
				'days_per_week'  	=> $daysPerWeek,
				'days_name'      	=> $daysNameString
				);

			$workWeekData  = $this->Mdl_work_week->insertUpdateRecord($workWeekArray, 'work_week_id', 'tbl_work_weeks', 1);

			if (isset($workWeekId) && $workWeekId != '') {
				if ($workWeekData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), WORKWEEK);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), WORKWEEK);
				}
			} else {
				if ($workWeekData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), WORKWEEK);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), WORKWEEK);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$workWeekId = $this->input->post('deleteId',TRUE);

		if( is_reference_in_table('work_week_id', 'tbl_employee', $workWeekId)) {
			$response['success'] = false;
			$response['msg'] = $this->lang->line('delete_record_dependency');
			echo json_encode($response);
			exit;
		}

		$workWeekData = $this->Mdl_work_week->deleteRecord($workWeekId);
		//delete Business Type
		if ($workWeekData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),WORKWEEK);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),WORKWEEK);
		}
		echo json_encode($response);
	}


	public function getWorkWeekDD()
	{
		$workWeekId   = $this->input->post("work_week_id");
		$searchTerm    = $this->input->post("filter_param");

		$data = array(
			"work_week_id"     => $workWeekId,
			"filter_param"     => $searchTerm
		);
		echo $this->Mdl_work_week->getWorkWeekDD($data);
	}
}
