<?php


class EmployeeType extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_employee_type"));
		$this->lang->load('employee_type');
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

		$this->dt_ci_template->load("default", "employeeType/v_employee_type", $data);
	}

	// ajax call to the data listing
	public function getEmployeeTypeListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("et.type_id,et.type_name,et.description");
		$this->datatables->from("tbl_employee_type as et");
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($typeId = '') // change here manage
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
		if($typeId != '') {
			$data['getEmployeeTypeData'] = $this->Mdl_employee_type->getEmployeeTypeData($typeId);
		}
		$this->dt_ci_template->load("default", "employeeType/v_employee_type_manage", $data);
	}


	// Save function here
	public function save()
	{
		$typeId    		= $this->input->post('type_id');
		$typeName  		= $this->input->post('type_name', TRUE);
		$description   	= $this->input->post('description', TRUE);
			
		if (isset($typeId) && $typeId == '') {
			$this->form_validation->set_rules('type_name', $this->lang->line('type_name'), 'required|is_unique[tbl_employee_type.type_name]');
		} else {
			$this->form_validation->set_rules('type_name', $this->lang->line('type_name'), 'required|edit_unique[tbl_employee_type.type_name.' . $typeId . ']');
		}
		
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('edit_unique', '%s is Already Exists');
		$this->form_validation->set_message('is_unique', '%s is Already Exists');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			
			$typeArray = array(
				'type_id'			=> $typeId,
				'type_name'  		=> $typeName,
				'description'  		=> $description,
			);
		
			$typeData  = $this->Mdl_employee_type->insertUpdateRecord($typeArray, 'type_id', 'tbl_employee_type', 1);

			if (isset($typeId) && $typeId != '') {
				if ($typeData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), EMPLOYEETYPE);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), EMPLOYEETYPE);
				}
			} else {
				if ($typeData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), EMPLOYEETYPE);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), EMPLOYEETYPE);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$typeId   = $this->input->post('deleteId',TRUE);
		$typeData = $this->Mdl_employee_type->deleteRecord($typeId);

		if ($typeData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),EMPLOYEETYPE);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),EMPLOYEETYPE);
		}
		echo json_encode($response);
	}
	
	public function getTypeDD()
	{
		$typeId      = $this->input->post("type_id");
		$searchTerm  = $this->input->post("filter_param");

		$data = array(
			"type_id"        => $typeId,
			"filter_param"  => $searchTerm
		);
		echo $this->Mdl_employee_type->getTypeDD($data);
	}
}
