<?php


class Employee extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_employee","Ion_auth_model","Mdl_common_model"));
		$this->lang->load('employee');
		$this->load->library('Curl');
	}

	public function index()
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/pages/form_layouts.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/ui/moment/moment.min.js",
			"js/plugins/pickers/anytime.min.js",
			//"js/pages/picker_date.js",
			"js/plugins/ui/ripple.min.js",
		);
		
		$select2 = array(
			'employeeTypeFilter' 		 =>true
		);
		$data['empName']  			= $this->Mdl_employee->getTotalEmployee();
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);
		$this->dt_ci_template->load("default", "employee/v_employee", $data);
	}


	public function getEmployeeListing()
	{
		$this->load->library('datatables');
		$response = $this->curl->simple_post('https://alitainfotech.com/sites/hrms/Api/EmployeeManage/getEmployeeListing', $_POST);
		$response = json_decode($response,1);
		$output = array(
			"draw" 			  => intval($_POST["draw"]),
			"recordsTotal" 	  => $this->Mdl_employee->getAllData(),
			"recordsFiltered" => $this->Mdl_employee->getFilteredData(),
			"data" => $response['data']
		);
		echo json_encode($output);
	}


	public function getEmployeeDataListing($employeeId = '')
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
			"/js/maps/jquery.geocomplete.js",
		);

		$select2 = array(
			'gender' 		 =>true,
			'maritalStatus'  =>true,
			'city' 			 =>true,
			'country' 		 =>true,
			'accountDetails' =>true,
			'department' 	 =>true,
			'salary' 	 	 =>true,
			'role' 	 	 	 =>true,
			'workWeek' 	 	 =>true,
			'company' 	 	 =>true,
			'designation' 	 =>true,
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if($employeeId != '') {
			$data['getEmployeeData']    = $this->Mdl_employee->getEmployeeInfoData($employeeId);
		}


		$this->dt_ci_template->load("default", "employee/v_employee_data",$data);
	}

	public function getCurrentEmployee($employeeId = '')
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

		if($employeeId != '') {
			$data['getCurrentEmployeeData']    = $this->Mdl_employee->getCurrentEmployeeData($employeeId);
		}


		$this->dt_ci_template->load("default", "employee/v_employees",$data);
	}

	public function manage($employeeId = '')
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/selects/select2.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/pages/form_layouts.js",
			"js/plugins/forms/jquery.form.min.js",
			"js/plugins/ui/moment/moment.min.js",
			"js/plugins/pickers/anytime.min.js",
			"js/core/libraries/jquery_ui/widgets.min.js",
			"js/plugins/media/fancybox.min.js",
			"js/pages/gallery.js",
		);

		$select2 = array(
			'gender' 		 =>true,
			'maritalStatus'  =>true,
			'state' 		 =>true,
			'city' 			 =>true,
			'country' 		 =>true,
			'accountDetails' =>true,
			'department' 	 =>true,
			'salary' 	 	 =>true,
			'role' 	 	 	 =>true,
			'workWeek' 	 	 =>true,
			'monthlyWorkingDays'  =>true,
			'company' 	 	 =>true,
			'employeeShift'  =>true,
			'designation'    =>true,
			'type'    		 =>true,
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);
		if($employeeId != '') {
			$data['getEmployeeData']    = $this->Mdl_employee->getEmployeeData($employeeId);
		}else{
		    $id = $this->Mdl_employee->getEmployeeCode();
			$data['getEmployeeData'] = array( 'employee_code' => $id);
		}
		$this->dt_ci_template->load("default", "employee/v_employee_manage",$data);
	}

	public function save()
	{
		$this->db->trans_begin();
		$employeeId 			= $this->input->post('emp_id', TRUE);
		$shiftId				= $this->input->post('shift_id', TRUE);
		$employeeCode 			= $this->input->post('employee_code', TRUE);
		$firstName 				= $this->input->post('first_name', TRUE);
		$lastName 				= $this->input->post('last_name', TRUE);
		$email 					= $this->input->post('email', TRUE);
		$password 				= $this->input->post('password', TRUE);
		$mobileNo 				= $this->input->post('mobile_no', TRUE);
		$genderId 				= $this->input->post('gender_id', TRUE);
		$maritalStatusId 		= $this->input->post('marital_status_id', TRUE);
		$birthDate 				= $this->input->post('birth_date', TRUE);
		$age 					= $this->input->post('age', TRUE);
		$hireDate 				= $this->input->post('hire_date', TRUE);
		$status 				= $this->input->post('status', TRUE);
		$typeId 				= $this->input->post('type_id', TRUE);
		$designation 			= $this->input->post('designation_id', TRUE);
		$departmentId 			= $this->input->post('department_id', TRUE);
		$address 				= $this->input->post('address', TRUE);
		$stateId 				= $this->input->post('state_id', TRUE);
		$cityId 				= $this->input->post('city_id', TRUE);
		$postalCode 			= $this->input->post('postal_code', TRUE);
		$countryId 				= $this->input->post('country_id', TRUE);
		$roleId 				= $this->input->post('role_id', TRUE);
		$workWeekId 			= $this->input->post('work_week_id', TRUE);
		$monthlyWorkingDaysId   = $this->input->post('monthly_working_days', TRUE);
		$employeeDetailsId 		= $this->input->post('employee_details_id', TRUE);
		$lastEmployeerName 		= $this->input->post('last_employeer_name', TRUE);
		$description 			= $this->input->post('description', TRUE);
		$accountDetailsId 		= $this->input->post('account_details_id', TRUE);
		$bankName 				= $this->input->post('bank_name', TRUE);
		$holderName 			= $this->input->post('holder_name', TRUE);
		$bankCode 				= $this->input->post('bank_code', TRUE);
		$accountNumber 			= $this->input->post('account_number', TRUE);
		$salaryId 				= $this->input->post('salary_id', TRUE);
		$amount 				= $this->input->post('amount', TRUE);


		if (isset($employeeId) && $employeeId == '') {
			$this->form_validation->set_rules('email', $this->lang->line('email'), 'required|is_unique[tbl_employee.email]');
			$this->form_validation->set_rules('employee_code', $this->lang->line('employee_code'), 'required|is_unique[tbl_employee.employee_code]');
			$this->form_validation->set_rules('password', $this->lang->line('password'), 'required');
		} else {
			$this->form_validation->set_rules('email', $this->lang->line('email'), 'required|edit_unique[tbl_employee.email.' .$employeeId. ']');
			$this->form_validation->set_rules('employee_code', $this->lang->line('employee_code'), 'required|edit_unique[tbl_employee.employee_code.' .$employeeId. ']');
		}

		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('numeric', '%s Please Enter Only Number');
		$this->form_validation->set_message('is_unique', 'This %s Already Exists');
		$this->form_validation->set_message('edit_unique', 'This %s Already Exists');

		$countryName = 'india';
		$result   	 = $this->Mdl_employee->getcountryId($countryName);

		if($countryId == $result['country_id']){
			$esic = $this->input->post('esic', TRUE);
			if($esic =='yes'){
				$ipNo = $this->input->post('ip_no') ;
				$this->form_validation->set_rules('ip_no', $this->lang->line('ip_no'), 'required');
			}else{
				$ipNo = "";
			}
			
		} else{
			$ipNo = "";
			$esic  = "";
		}
		if($countryId == $result['country_id']){
			$epf = $this->input->post('epf', TRUE);
			if($epf == 'yes' ){
				$uanNo = $this->input->post('uan_no') ;
				$this->form_validation->set_rules('uan_no', $this->lang->line('uan_no'), 'required');
			}else{
				$uanNo = "";
			}
		} else{
			$uanNo = "";
			$epf ="";
		}

		if($countryId == $result['country_id']){
			$panCardNo 		= $this->input->post('pan_card_no', TRUE);
			$aadharCardNo 	= $this->input->post('aadhar_card_no', TRUE);
			$this->form_validation->set_rules('pan_card_no', $this->lang->line('pan_card_no'), 'required');
			$this->form_validation->set_rules('aadhar_card_no', $this->lang->line('aadhar_card_no'), 'required');
		} else{
			$panCardNo 	  = "";
			$aadharCardNo = "";
		}

		$this->form_validation->set_rules('shift_id', $this->lang->line('shift'), 'required');
		$this->form_validation->set_rules('last_name', $this->lang->line('last_name'), 'required');
		$this->form_validation->set_rules('email', $this->lang->line('email'), 'required');
		$this->form_validation->set_rules('mobile_no', $this->lang->line('mobile_no'), 'required');
		$this->form_validation->set_rules('gender_id', $this->lang->line('gender'), 'required');
		$this->form_validation->set_rules('marital_status_id', $this->lang->line('marital_status'), 'required');
		$this->form_validation->set_rules('birth_date', $this->lang->line('birth_date'), 'required');
		$this->form_validation->set_rules('age', $this->lang->line('age'), 'required');
		$this->form_validation->set_rules('hire_date', $this->lang->line('hire_date'), 'required');
		$this->form_validation->set_rules('type_id', $this->lang->line('type'), 'required');
		$this->form_validation->set_rules('designation_id', $this->lang->line('designation'), 'required');
		$this->form_validation->set_rules('department_id', $this->lang->line('department'), 'required');
		$this->form_validation->set_rules('address', $this->lang->line('address'), 'required');
		$this->form_validation->set_rules('state_id', $this->lang->line('state'), 'required');
		$this->form_validation->set_rules('city_id', $this->lang->line('city'), 'required');
		$this->form_validation->set_rules('postal_code', $this->lang->line('postal_code'), 'required');
		$this->form_validation->set_rules('country_id', $this->lang->line('country'), 'required');
		$this->form_validation->set_rules('role_id', $this->lang->line('role'), 'required');
		$this->form_validation->set_rules('work_week_id', $this->lang->line('work_week'), 'required');
		$this->form_validation->set_rules('monthly_working_days', $this->lang->line('monthly_working_days'), 'required');
		$this->form_validation->set_rules('bank_name', $this->lang->line('bank_name'), 'required');
		$this->form_validation->set_rules('bank_code', $this->lang->line('bank_code'), 'required');
		$this->form_validation->set_rules('holder_name', $this->lang->line('holder_name'), 'required');
		$this->form_validation->set_rules('account_number', $this->lang->line('account_number'), 'required');
		$this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {

			$password = $this->Ion_auth_model->hash_password($password);
			if (isset($employeeId) && $employeeId == '') {
				$employeeArray = array(
					'emp_id' 			=> $employeeId,
					'shift_id' 			=> $shiftId,
					'employee_code' 	=> $employeeCode,
					'first_name' 		=> $firstName,
					'last_name' 		=> $lastName,
					'email' 			=> $email,
					'password' 			=> $password,
					'mobile_no' 		=> $mobileNo,
					'gender_id' 		=> $genderId,
					'marital_status_id' => $maritalStatusId,
					'birth_date' 		=> DMYToYMD($birthDate),
					'age' 				=> $age,
					'hire_date' 		=> DMYToYMD($hireDate),
					'status' 			=> isset($status) ? 1 : 0,
					'type_id' 			=> $typeId,
					'designation_id' 	=> $designation,
					'department_id' 	=> $departmentId,
					'address' 			=> $address,
					'state_id' 			=> $stateId,
					'city_id' 			=> $cityId,
					'postal_code' 		=> $postalCode,
					'aadhar_card_no' 	=> $aadharCardNo,
					'pan_card_no' 		=> $panCardNo,
					'country_id' 		=> $countryId,
					'role_id' 			=> $roleId,
					'work_week_id' 		=> $workWeekId,
					'month_work_id' 	=> $monthlyWorkingDaysId,
					'company_id' 		=> 1,
				);
			}  else{
				$employeeArray = array(
					'emp_id' 			=> $employeeId,
					'shift_id' 			=> $shiftId,
					'first_name' 		=> $firstName,
					'employee_code' 	=> $employeeCode,
					'last_name' 		=> $lastName,
					'email' 			=> $email,
					'mobile_no' 		=> $mobileNo,
					'gender_id' 		=> $genderId,
					'marital_status_id' => $maritalStatusId,
					'birth_date' 		=> DMYToYMD($birthDate),
					'age' 				=> $age,
					'hire_date' 		=> DMYToYMD($hireDate),
					'status' 			=> isset($status) ? 1 : 0,
					'type_id' 			=> $typeId,
					'designation_id' 	=> $designation,
					'department_id' 	=> $departmentId,
					'address' 			=> $address,
					'state_id' 			=> $stateId,
					'city_id' 			=> $cityId,
					'aadhar_card_no' 	=> $aadharCardNo,
					'pan_card_no' 		=> $panCardNo,
					'postal_code' 		=> $postalCode,
					'country_id' 		=> $countryId,
					'role_id' 			=> $roleId,
					'work_week_id' 		=> $workWeekId,
					'month_work_id' 	=> $monthlyWorkingDaysId,
					'company_id' 		=> 1,
					);
				}

				$employeeData = $this->Mdl_employee->insertUpdateRecord($employeeArray, 'emp_id', 'tbl_employee', 1);
				$lastEmployeeId = $employeeData['lastInsertedId'];
				if (isset($lastEmployeeId) && $lastEmployeeId != '') {
					if ($employeeData['success']) {
						
						if($employeeId == ''){
							$loginTable = $this->Mdl_employee->insertInLogin($lastEmployeeId);
						}
						
						if (isset($_FILES['filename'])) {
							$memberImagePath = $this->config->item('employee_image');
							$imageResult = $this->dt_ci_file_upload->UploadFile('filename', MAX_IMAGE_SIZE_LIMIT, $memberImagePath, true, true, array('jpeg', 'png', 'jpg', 'JPG'));
							if ($imageResult['success'] == false) {
								$response['success'] = false;
								$response['msg'] = strip_tags($imageResult['msg']);
								echo json_encode($response);
								die();
							} else {
								unset($imageResult['success']);
								$batchArray = array(
									'emp_id' 	=> $lastEmployeeId,
									'filename'  => $imageResult['file_name'],
								);
								$this->Mdl_employee->insertUpdateEmployeeImageEntry($batchArray);
							}
						}
					}
				}

				$lastEmployeeId = $employeeData['lastInsertedId'];
				if(isset($lastEmployeeId) && $lastEmployeeId != ''){
					if ($employeeData['success']) {
						if($employeeDetailsId != ''){
							$employeeDetailsUpdateArray[] = array(
								'employee_details_id'  => $employeeDetailsId,
								'emp_id'  			   => $lastEmployeeId,
								'last_employeer_name'  => $lastEmployeerName,
								'description'  		   => $description,
							);
						} else {
							if($lastEmployeerName != "" ){
								$employeeDetailsInsertArray[] = array(
									'emp_id'  			   => $lastEmployeeId,
									'last_employeer_name'  => $lastEmployeerName,
									'description'  		   => $description,
								);
							}
						}
					}
					if(!empty($employeeDetailsInsertArray)){
						$this->Mdl_employee->batchInsert($employeeDetailsInsertArray, 'tbl_employee_details');
					}else if(!empty($employeeDetailsUpdateArray)){
						$this->Mdl_employee->batchUpdate($employeeDetailsUpdateArray,'employee_details_id' ,'tbl_employee_details');
					}
				}

				$lastEmployeeId = $employeeData['lastInsertedId'];
				if(isset($lastEmployeeId) && $lastEmployeeId != ''){
					if ($employeeData['success']) {
						if($accountDetailsId != ''){
							$accountDetailsUpdateArray[] = array(
								'account_details_id'   => $accountDetailsId,
								'emp_id'  			   => $lastEmployeeId,
								'bank_name'  		   => $bankName,
								'holder_name'  		   => $holderName,
								'bank_code'  		   => $bankCode,
								'account_number'  	   => $accountNumber,
							);
						} else {
							$accountDetailsInsertArray[] = array(
								'emp_id'  			   => $lastEmployeeId,
								'bank_name'  		   => $bankName,
								'holder_name'  		   => $holderName,
								'bank_code'  		   => $bankCode,
								'account_number'  	   => $accountNumber,
							);
						}
					}
					if(!empty($accountDetailsInsertArray)){
						$this->Mdl_employee->batchInsert($accountDetailsInsertArray, 'tbl_account_details');
					}
					if(!empty($accountDetailsUpdateArray)){
						$this->Mdl_employee->batchUpdate($accountDetailsUpdateArray,'account_details_id' ,'tbl_account_details');
					}
				}

				$lastEmployeeId = $employeeData['lastInsertedId'];
				if(isset($lastEmployeeId) && $lastEmployeeId != ''){
					if ($employeeData['success']) {
						if($salaryId != ''){
							$salaryUpdateArray[] = array(
								'salary_id'   => $salaryId,
								'emp_id'  	  => $lastEmployeeId,
								'amount'  	  => $amount,
								'esic'  	  => $esic,
								'ip_no'  	  => $ipNo,
								'epf'  	   	  => $epf,
								'uan_no'  	  => $uanNo,
							);
						} else {
							$salaryInsertArray[] = array(
								'emp_id'  	  => $lastEmployeeId,
								'amount'  	  => $amount,
								'esic'  	  => $esic,
								'ip_no'  	  => $ipNo,
								'epf'  	   	  => $epf,
								'uan_no'  	  => $uanNo,
							);
						}
					}
					if(!empty($salaryInsertArray)){
						$this->Mdl_employee->batchInsert($salaryInsertArray, 'tbl_salary');
					}
					if(!empty($salaryUpdateArray)){
						$this->Mdl_employee->batchUpdate($salaryUpdateArray,'salary_id' ,'tbl_salary');
					}
				}

				if (isset($employeeId) && $employeeId != '') {
					if ($employeeData['success']) {
						$this->db->trans_commit();
						$response['success'] = true;
						$response['msg'] 	 = sprintf($this->lang->line('update_record'), EMPLOYEE);
					} else {
						$this->db->trans_rollback();
						$response['success'] = false;
						$response['msg'] 	 = sprintf($this->lang->line('update_record_error'), EMPLOYEE);
					}
				} else {
					if ($employeeData['success']) {
						$this->db->trans_commit();
						$response['success'] = true;
						$response['msg'] 	 = sprintf($this->lang->line('create_record'), EMPLOYEE);
					} else {
					$this->db->trans_rollback();
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('create_record_error'), EMPLOYEE);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete(){
		$employeeId     = $this->input->post('deleteId',TRUE);
		
		if($employeeId != ''){
			$imageArray = $this->Mdl_employee->getImage($employeeId);
		}
		if(count($imageArray) > 0) {
			$getImage = array_column($imageArray,'filename');
			$this->Mdl_employee->unlinkFile('employee_image', $getImage);
		}

		$employeeData   = $this->Mdl_employee->deleteRecord($employeeId);
		if ($employeeData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),EMPLOYEE);
		} else {
			$response['success'] = false;
			$response['msg'] = sprintf($this->lang->line('error_delete_record'),EMPLOYEE);
		}
		echo json_encode($response);
		die();
	}

	public function imageLoad()
	{
		$employeeId                 = $this->input->post('emp_id');
		$this->data['imageList']    = $this->Mdl_employee->getImage($employeeId);
		$result 					= $this->load->view("employee/v_employee_image_list", $this->data,true);
		echo json_encode($result);
	}

	public function imageDelete()
	{
		$imageUrl = $this->input->post('imageUrl');
		$imageId  = $this->input->post('imageId');

		$galleryImageData =  $this->Mdl_employee->deleteEmployeeImageEntry($imageId);

		if ($galleryImageData['success']) {
			if (file_exists($imageUrl)) {
				unlink($imageUrl);
			}

			$response['success']  = true;
			$response['msg']      = $this->lang->line('gallery_image_delete');
		} else {
			$response['success']  = false;
			$response['msg']      = $this->lang->line('gallery_image_delete_error');
		}
		echo json_encode($response);
		die();
	}

	public function getEmployeeListDD()
	{
		$empId     	 = $this->input->post("emp_id");
		$searchTerm  = $this->input->post("filter_param");
		$allEmployee = $this->input->post("all_employee");
		$empIds      = $this->input->post("emp_ids");
		$exEmp       = $this->input->post("ex_emp");
		
		$data = array(
			"emp_id"        => $empId,
			"filter_param"  => $searchTerm,
			"all_employee"  => $allEmployee,
			"emp_ids"       => json_decode($empIds),
			"ex_emp"			=> $exEmp
		);
		echo $this->Mdl_employee->getEmployeeListDD($data);
	}
	public function getemployeeTypeFilterDD()
	{
		$empId     	 = $this->input->post("employeeTypeFilterId");
		$searchTerm  = $this->input->post("filter_param");

		$data = array('result' => array(
			
		 array(
			"id"        => 'CurrentEmployee',
			"text"      => 'Current Employee'),
		array(
			"id"        => 'PastEmployee',
			"text"      => 'Past Employee'),
		array(
			"id"        => '0',
			"text"      => 'All Employee')
		));

		echo json_encode($data); die;
	}
	
	
	public function getTeamHeadListDD()
	{
		$empId     	 = $this->input->post("emp_id");
		$searchTerm  = $this->input->post("filter_param");

		$data = array(
			"emp_id"        => $empId,
			"filter_param"  => $searchTerm
		);
		echo $this->Mdl_employee->getTeamHeadListDD($data);
	}
	
	public function getTeamMembersListDD()
	{
		$empId     	 = $this->input->post("emp_id");
		$searchTerm  = $this->input->post("filter_param");
		$teamHeadId =  $this->input->post("teamhead_id");

		$data = array(
			"emp_id"        => $empId,
			"teamhead_id"        => $teamHeadId,
			"filter_param"  => $searchTerm
		);
		echo $this->Mdl_employee->getTeamMembersListDD($data);
	}
	public function getTLMembersDD()
	{
		$empId     	 = $this->input->post("emp_id");
		$searchTerm  = $this->input->post("filter_param");
		$teamHeadId =  $this->input->post("team_head_id");

		$data = array(
			"emp_id"        => $empId,
			"team_head_id"  => $teamHeadId,
			"filter_param"  => $searchTerm
		);
		echo $this->Mdl_employee->getTLMembersDD($data);
	}
}
