	<?php


class Company extends DT_CI_Controller
{

	public function __construct(){
		parent::__construct();
		$this->load->library('Curl');
		$this->load->model("Mdl_company");
		$this->lang->load('company');
	}

	public function index(){
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/forms/selects/select2.min.js",
		);

		$this->dt_ci_template->load("default", "company/v_company", $data);
	}


	public function getCompanyListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("tcom.company_id,tcom.company_name,tcom.site_name,tcom.email,tcom.is_active");
		$this->datatables->from("tbl_companies as tcom");
		echo $this->datatables->generate();
	}

	public function manage($companyId = '')
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
			'city' 			 =>true,
			'state' 		 =>true,
			'country' 		 =>true
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);
		if($companyId != '') {
			$data['getCompanyData']         = $this->Mdl_company->getCompanyData($companyId);

		}
		$this->dt_ci_template->load("default", "company/v_company_manage",$data);
	}

	public function getCompanyDataListing($empId='',$companyId = '')
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
			'city' 			 =>true,
			'state' 		 =>true,
			'country' 		 =>true
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if($companyId != '') {
			$data['getCompanyInfoData']    = $this->Mdl_company->getCompanyInfoData($companyId);

		}
		$this->dt_ci_template->load("default", "company/v_company_data",$data);
	}

	public function save()
	{
//		$this->db->trans_begin();
		$companyId         = $this->input->post('company_id',TRUE);
		$companyName       = $this->input->post('company_name', TRUE);
		$email       	   = $this->input->post('email', TRUE);
		$description       = $this->input->post('description', TRUE);
		$address           = $this->input->post('address', TRUE);
		$countryId         = $this->input->post('country_id', TRUE);
		$stateId           = $this->input->post('state_id', TRUE);
		$cityId            = $this->input->post('city_id', TRUE);
		$postalCode        = $this->input->post('postal_code', TRUE);
		$siteName          = $this->input->post('site_name', TRUE);
		$siteUrl           = $this->input->post('site_url', TRUE);
		$phoneNo           = $this->input->post('phone_no', TRUE);
		$faxNo             = $this->input->post('fax_no', TRUE);
		$isActive          = $this->input->post('is_active', TRUE);


		if(isset($companyId) && $companyId == ''){
			$this->form_validation->set_rules('company_name', $this->lang->line('company_name'), 'required|is_unique[tbl_companies.company_name]');
			$this->form_validation->set_rules('email', $this->lang->line('email'), 'required|is_unique[tbl_companies.email]');
		} else{
			$this->form_validation->set_rules('company_name', $this->lang->line('company_name'), 'required|edit_unique[tbl_companies.company_name.'.$companyId.']');
			$this->form_validation->set_rules('email', $this->lang->line('email'), 'required|edit_unique[tbl_companies.email.'.$companyId.']');
		}

		$this->form_validation->set_rules('description', $this->lang->line('description'), 'required');
		$this->form_validation->set_rules('address', $this->lang->line('address'), 'required');
		$this->form_validation->set_rules('country_id', $this->lang->line('country'), 'required');
		$this->form_validation->set_rules('state_id', $this->lang->line('state'), 'required');
		$this->form_validation->set_rules('city_id', $this->lang->line('city'), 'required');
		$this->form_validation->set_rules('postal_code', $this->lang->line('postal_code'), 'required');
		$this->form_validation->set_rules('site_name', $this->lang->line('site_name'), 'required');
		$this->form_validation->set_rules('site_url', $this->lang->line('site_url'), 'required');
		$this->form_validation->set_rules('phone_no', $this->lang->line('phone_no'), 'required');
		$this->form_validation->set_rules('fax_no', $this->lang->line('fax_no'), 'required');
		$this->form_validation->set_rules('is_active', $this->lang->line('is_active'), 'required');

		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('numeric', '%s Please Enter Only Number');
		$this->form_validation->set_message('is_unique', 'This %s Already Exists');
		$this->form_validation->set_message('edit_unique', 'This %s Already Exists');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$companyArray = array(
				'company_id'	 => $companyId,
				'company_name' 	 => $companyName,
				'email' 	 	 => $email,
				'description' 	 => $description,
				'country_id' 	 => $countryId,
				'state_id' 	 	 => $stateId,
				'city_id' 	 	 => $cityId,
				'address' 	 	 => $address,
				'postal_code' 	 => $postalCode,
				'site_name' 	 => $siteName,
				'site_url' 	 	 => $siteUrl,
				'phone_no' 	 	 => $phoneNo,
				'fax_no' 	 	 => $faxNo,
				'is_active' 	 => isset($isActive) ? 1 : 0,
			);

			$companyData = $this->Mdl_company->insertUpdateRecord($companyArray, 'company_id', 'tbl_companies', 1);

			if (isset($companyId) && $companyId != '') {
				if ($companyData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), COMPANY);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), COMPANY);
				}
			} else {
				if ($companyData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), COMPANY);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), COMPANY);
				}
			}
			echo json_encode($response);
		}
	}

	public function changeActive()
	{
		$companyId   = $this->input->post('company_id', TRUE);
		$status      = $this->input->post('status', TRUE);

		if ($status == 0) {
			$status = 1;
		} else {
			$status = 0;
		}
		$return = $this->Mdl_company->statusChange($companyId, $status, 'company_id', 'tbl_companies');
		if ($return == 1)
		{
			$response['success']  = true;
			$response['msg']      = sprintf($this->lang->line('status_change'), COMPANY);
		}
		else
		{
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('status_change_error'), COMPANY);
		}
		echo json_encode($response);

	}

	public function delete()
	{
		$companyId = $this->input->post('deleteId',TRUE);
		$ids = is_array($companyId) ? implode(',',$companyId) : $companyId;
		
		 if( is_reference_in_table('company_id', 'tbl_employee', $companyId)){
            $response['success'] = false;
            logActivity('Tried to delete Company [CompanyID: ' . $ids . ']',$this->data['userId'],'Company');
            $response['msg'] = $this->lang->line('delete_record_dependency');
            echo json_encode($response);
            exit;
        }


		//delete monk location
		$companyData = $this->curl->simple_post('http://alitainfotech.com/Employee_Management/Api/EmployeeManage/deleteCompany', array('company_id'=>$ids));
		$companyData = json_decode($companyData);


		if ($companyData->status == "true") {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),COMPANY);
		} else {
			$response['success'] = false;
			$response['msg'] = sprintf($this->lang->line('error_delete_record'),COMPANY);
		}
		echo json_encode($response);
	}

	public function getCompanyDD()
	{
		$filterParameter 		= $this->input->post('filter_param');
		$companyIdActive 		= $this->input->post('companyIdActive');
		$page = $this->input->post('page');
		echo $this->Mdl_company->getCompanyDD($filterParameter,$page,$companyIdActive);
	}


}
