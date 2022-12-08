<?php
class Salary extends DT_CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library('Curl');
		$this->load->model(array("Mdl_salary"));
		$this->lang->load('salary');
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

		$this->dt_ci_template->load("default", "salary/v_salary", $data);
	}

	// ajax call to the data listing
	public function getSalaryListing()
	{
//		$this->load->library('datatables');
//		$response = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/getSalaryListing', $_POST);
//		$response = json_decode($response,1);
//		$output = array(
//			"draw" 			  => intval($_POST["draw"]),
//			"recordsTotal" 	  => $this->Mdl_salary->getAllData(),
//			"recordsFiltered" => $this->Mdl_salary->getFilteredData(),
//			"data" => $response['data']
//		);
//		echo json_encode($output);
		$this->load->library('datatables');
		$this->datatables->select("ts.salary_id,ts.amount,ts.esic,ts.pf");
		$this->datatables->from("tbl_salary as ts");
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($salaryId = '') // change here manage
	{

		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/forms/selects/select2.min.js",
		);
		if ($salaryId != '') {
			$data['getSalaryData'] = $this->Mdl_salary->getSalaryData($salaryId);

		}

		$this->dt_ci_template->load("default", "salary/v_salary_manage", $data);
	}


	// Save function here
	public function save()
	{
		$salaryId 	= $this->input->post('salary_id');
		$amount		= $this->input->post('amount');
		$esic 		= $this->input->post('esic', TRUE);
		$pf 		= $this->input->post('pf', TRUE);

		if(isset($salaryId) && $salaryId == ''){
			$this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|is_unique[tbl_salary.amount]');
		} else{
			$this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|edit_unique[tbl_salary.amount.'.$salaryId.']');
		}
		$this->form_validation->set_rules('esic', $this->lang->line('esic'), 'required');
		$this->form_validation->set_rules('pf', $this->lang->line('pf'), 'required');
		$this->form_validation->set_message('required', '%s is required');

		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$salaryArray = array(
				'salary_id'	=> $salaryId,
				'amount' 	=> $amount,
				'esic' 	 	=> $esic,
				'pf' 	 	=> $pf
			);

			$salaryData = $this->Mdl_salary->insertUpdateRecord($salaryArray, 'salary_id', 'tbl_salary', 1);
			
			if (isset($salaryId) && $salaryId != '') {
				if ($salaryData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), SALARY);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), SALARY);
				}
			} else {
				if ($salaryData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), SALARY);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), SALARY);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$salaryId = $this->input->post('deleteId', TRUE);
		$ids = is_array($salaryId) ? implode(',',$salaryId) : $salaryId;

		if (is_reference_in_table('salary_id', 'tbl_employee', $salaryId)) {

			$response['success'] = false;
			$response['msg'] = $this->lang->line('delete_record_dependency');
			echo json_encode($response);
			exit;


		}

		//delete  member
		//$salaryData = $this->curl->simple_post('http://alitainfotech.com/Employee_Management/Api/EmployeeManage/deleteSalary', array('salary_id'=>$ids));
		$salaryData = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/deleteSalary', array('salary_id'=>$ids));
		$salaryData = json_decode($salaryData);
		
		if ($salaryData->status == "true") {
			$response['success'] = true;
			$response['msg'] = sprintf($this->lang->line('delete_record'), SALARY);
		} else {
			$response['success'] = false;
			$response['msg'] = sprintf($this->lang->line('error_delete_record'), SALARY);
		}
		echo json_encode($response);
	}


	public function getSalaryDD()
	{
		$salaryId 		= $this->input->post("salary_id");
		$searchTerm 	= $this->input->post("filter_param");

		$data = array(
			"salary_id" 	=> $salaryId,
			"filter_param" => $searchTerm
		);
		echo $this->Mdl_salary->getSalaryDD($data);
	}

}
