<?php


class Designation extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_designation"));
		$this->lang->load('designation');
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

		$this->dt_ci_template->load("default", "designation/v_designation", $data);
	}

	// ajax call to the data listing
	public function getDesignationListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("td.designation_id,td.designation_name,td.description");
		$this->datatables->from("tbl_designation as td");
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($designationId = '') // change here manage
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
		if($designationId != '') {
			$data['getDesignationData'] = $this->Mdl_designation->getDesignationData($designationId);

		}

		$this->dt_ci_template->load("default", "designation/v_designation_manage", $data);
	}



	// Save function here
	public function save()
	{
		$designationId       = $this->input->post('designation_id');
		$designationName     = $this->input->post('designation_name');
		$description     	 = $this->input->post('description');

		if(isset($designationId) && $designationId == ''){
			$this->form_validation->set_rules('designation_name', $this->lang->line('designation_name'), 'required|is_unique[tbl_designation.designation_name]');
		} else{
			$this->form_validation->set_rules('designation_name', $this->lang->line('designation_name'), 'required|edit_unique[tbl_designation.designation_name.'.$designationId.']');
		}
		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$designationArray = array(
				'designation_id'	    => $designationId,
				'designation_name'  	=> $designationName,
				'description'  			=> $description,
			);

			$designationData  = $this->Mdl_designation->insertUpdateRecord($designationArray, 'designation_id', 'tbl_designation', 1);

			if (isset($designationId) && $designationId != '') {
				if ($designationData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), DESIGNATION);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), DESIGNATION);
				}
			} else {
				if ($designationData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), DESIGNATION);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), DESIGNATION);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$designationId = $this->input->post('deleteId',TRUE);

		if( is_reference_in_table('designation_id', 'tbl_employee', $designationId)) {
			$response['success'] = false;
			$response['msg'] = $this->lang->line('delete_record_dependency');
			echo json_encode($response);
			exit;
		}

		$designationData = $this->Mdl_designation->deleteRecord($designationId);
		if ($designationData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),DESIGNATION);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),DESIGNATION);
		}
		echo json_encode($response);
	}

	public function getDesignationDD()
	{
		$designationId 	= $this->input->post("designation_id");
		$searchTerm 	= $this->input->post("filter_param");

		$data = array(
			"designation_id" => $designationId,
			"filter_param" 	 => $searchTerm
		);
		echo $this->Mdl_designation->getDesignationDD($data);
	}
}
