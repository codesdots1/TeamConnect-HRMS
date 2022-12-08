<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . 'libraries/REST_Controller.php';

class EmployeeManage extends REST_Controller
{
	function __construct()
	{
		parent::__construct();

		$this->load->model(array(
			"Mdl_account_details",
			"Mdl_employee_webservice",
			"Mdl_state",
			"Mdl_city",
			"Mdl_country",
			"Mdl_department",
			"Mdl_salary",
			"Mdl_role",
			"Mdl_employee",
			"Mdl_company",
			"Mdl_gender",
			"Mdl_marital_status",
			"Mdl_employee_leave_type",
			"Mdl_leave_type",
			"Mdl_work_week",
		));
	}

	public function getCountryListing_post()
	{
		$countryData = $this->Mdl_country->getCountryListing();
		if (!empty($countryData)) {
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'	=> self::HTTP_OK,
				"message" 		=> 'Country Listing successfully',
				'limit' 		=> DATA_LIMIT,
				'data' 			=> $countryData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				'status' 		=> False,
				'responseCode'	=> self::HTTP_OK,
				"message" 		=> "No Data Found",
				'data' 			=> [],
			), REST_Controller::HTTP_OK);


		}
	}

	public function addEditCountry_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('country_id', 'Country Id', 'integer');
		$this->form_validation->set_rules('country_name', 'Country Name', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$countryId   	= $this->input->post('country_id');
		$countryName    = $this->input->post('country_name', true);
		$isActive      	= $this->input->post('is_active', true);

		$countryArray = array(
			'country_id'    => $countryId,
			'country_name'  => $countryName,
			'is_active'     => $isActive,
		);
		if ((isset($countryId) && $countryId != '')) {
			$successMessage = 'Country updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($countryArray['country_id']);
			$successMessage = 'Country inserted successfully';
			$errorMessage = 'Country insert unsuccessful';
		}
		$countryData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($countryArray, 'country_id', 'tbl_country', 1);

		if ($countryData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' => TRUE,
				'responseCode' => self::HTTP_OK,
				'message' => $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' => FALSE,
				'responseCode' => self::HTTP_NOT_FOUND,
				'message' => $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteCountry_post()
	{
		$countryId = $this->input->post('country_id');
		
		$this->form_validation->set_rules('country_id', 'Country Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');

		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => [],
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}
	   	
		$countryData = $this->Mdl_country->deleteRecord($countryId);
		

		if ($countryData['success'] == "true") {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Country Deleted successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $countryData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "Country Failed to delete",
				'limit'         => DATA_LIMIT,
			), REST_Controller::HTTP_OK);
		}
	}

	public function getStateListing_post()
	{
		$stateData = $this->Mdl_state->getStateListing();
		if (!empty($stateData)) {
			$this->response(array(
				'status'       => TRUE,
				'responseCode' => self::HTTP_OK,
				"message"      => 'State Listing successfully',
				'limit'        => DATA_LIMIT,
				'data'         => $stateData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "No Data Found",
				"data"          => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditState_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('country_id', 'Country Id', 'required|integer');
		$this->form_validation->set_rules('state_name', 'State Name', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$countryId   	= $this->input->post('country_id');
		$stateId   		= $this->input->post('state_id');
		$stateName    	= $this->input->post('state_name', true);
		$isActive      	= $this->input->post('is_active', true);

		$stateArray = array(
			'state_id'    	=> $stateId,
			'country_id'    => $countryId,
			'state_name'  	=> $stateName,
			'is_active'     => isset($isActive) ? 1 : 0,
		);
		if ((isset($stateId) && $stateId != '')) {
			$successMessage = 'State updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($stateArray['state_id']);
			$successMessage = 'State inserted successfully';
			$errorMessage = 'State insert unsuccessful';
		}
		$stateData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($stateArray, 'state_id', 'tbl_state', 1);

		if ($stateData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'  => self::HTTP_OK,
				'message'	 	=> $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' 		=> FALSE,
				'responseCode' 	=> self::HTTP_NOT_FOUND,
				'message' 		=> $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteState_post()
	{
		$stateId = $this->input->post('state_id');
		$this->form_validation->set_rules('state_id', 'State Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => null,
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}

		$stateData = $this->Mdl_state->deleteRecord($stateId);

		if ($stateData['success'] == "true") {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'State Deleted successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $stateData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "State Failed to delete",
				'limit'         => DATA_LIMIT,
			), REST_Controller::HTTP_OK);
		}
	}

	public function getCityListing_post()
	{
		$cityData = $this->Mdl_city->getCityListing();
		if (!empty($cityData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'City Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $cityData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "No Data Found",
				"data"          => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditCity_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('state_id', 'State Id', 'required|integer');
		$this->form_validation->set_rules('city_name', 'City Name', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$cityId   	= $this->input->post('city_id');
		$stateId   		= $this->input->post('state_id');
		$cityName    	= $this->input->post('city_name', true);
		$isActive      	= $this->input->post('is_active', true);

		$cityArray = array(
			'city_id'    	=> $cityId,
			'state_id'      => $stateId,
			'city_name'  	=> $cityName,
			'is_active'     => isset($isActive) ? 1 : 0,
		);
		if ((isset($cityId) && $cityId != '')) {
			$successMessage = 'City updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($cityArray['city_id']);
			$successMessage = 'City inserted successfully';
			$errorMessage = 'City insert unsuccessful';
		}
		$cityData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($cityArray, 'city_id', 'tbl_city', 1);

		if ($cityData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'  => self::HTTP_OK,
				'message'	 	=> $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' 		=> FALSE,
				'responseCode' 	=> self::HTTP_NOT_FOUND,
				'message' 		=> $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteCity_post()
	{
		$cityId = $this->input->post('city_id');
		$this->form_validation->set_rules('city_id', 'City Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => null,
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}

		$cityData = $this->Mdl_city->deleteRecord($cityId);
		
		if ($cityData['success'] == "true") {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'City Deleted successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $cityData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "City Failed to delete",
				'limit'         => DATA_LIMIT,
			), REST_Controller::HTTP_OK);
		}
	}

	public function getAccountDetailsListing_post()
	{
		$accountDetailsData = $this->Mdl_account_details->getAccountDetailsList();
		if (!empty($accountDetailsData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Account Details Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $accountDetailsData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditAccountDetails_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('account_details_id', 'Account Details Id', 'integer');
		$this->form_validation->set_rules('bank_name', 'Bank Name', 'required');
		$this->form_validation->set_rules('holder_name', 'Holder Name', 'required');
		$this->form_validation->set_rules('bank_code', 'Bank Code', 'required');
		$this->form_validation->set_rules('account_number', 'Account Number', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$accountDetailsId   = $this->input->post('account_details_id');
		$bankName      		= $this->input->post('bank_name', true);
		$holderName      	= $this->input->post('holder_name', true);
		$bankCode          	= $this->input->post('bank_code', true);
		$accountNumber      = $this->input->post('account_number', true);

		$accountDetailsArray = array(
			'account_details_id'    => $accountDetailsId,
			'bank_name'       		=> $bankName,
			'holder_name'      	 	=> $holderName,
			'bank_code'       		=> $bankCode,
			'account_number'       	=> $accountNumber,
		);
		if ((isset($accountDetailsId) && $accountDetailsId != '')) {
			$successMessage = 'Account Details updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($accountDetailsArray['account_details_id']);
			$successMessage = 'Account Details inserted successfully';
			$errorMessage = 'Account Details insert unsuccessful';
		}
		$employeeData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($accountDetailsArray, 'account_details_id', 'tbl_account_details', 1);

		if ($employeeData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' => TRUE,
				'responseCode' => self::HTTP_OK,
				'message' => $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' => FALSE,
				'responseCode' => self::HTTP_NOT_FOUND,
				'message' => $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteAccountDetails_post()
	{
		$accountDetailsId = $this->input->post('account_details_id');
		$this->form_validation->set_rules('account_details_id', 'Account Details Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => [],
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}

		$accountDetailsData = $this->Mdl_account_details->deleteRecord($accountDetailsId);

		if ($accountDetailsData['success'] == "true") {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Account Details Deleted successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $accountDetailsData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "Business Failed to delete",
				'limit'         => DATA_LIMIT,
				"data"          => $accountDetailsData,
			), REST_Controller::HTTP_OK);
		}
	}

	public function getEmployeeListing_post()
	{
		$employeeData 	= $this->Mdl_employee->getEmployeeListing();
	
		if (!empty($employeeData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Employee Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $employeeData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditEmployee_post()
	{
		$this->form_validation->set_rules('first_name', 'First Name', 'required');
		$this->form_validation->set_rules('last_name', 'Last Name', 'required');
		$this->form_validation->set_rules('email', 'Email', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('mobile_no', 'Mobile Number', 'required');
		$this->form_validation->set_rules('gender_id', 'Gender', 'required|integer');
		$this->form_validation->set_rules('marital_status_id', 'Marital Status', 'required|integer');
		$this->form_validation->set_rules('birth_date', 'Birth Date', 'required');
		$this->form_validation->set_rules('age', 'Age', 'required');
		$this->form_validation->set_rules('hire_date', 'Hire Date', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_rules('type', 'Type', 'required');
		$this->form_validation->set_rules('designation', 'Designation', 'required');
		$this->form_validation->set_rules('department_id', 'Department', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('city_id', 'City', 'required');
		$this->form_validation->set_rules('postal_code', 'Postal Code', 'required|integer');
		$this->form_validation->set_rules('country_id', 'Country', 'required');
		$this->form_validation->set_rules('salary_id', 'Salary', 'required');
		$this->form_validation->set_rules('account_details_id', 'Account Details', 'required');
		$this->form_validation->set_rules('work_week_id', 'Work Week', 'required');
		$this->form_validation->set_rules('company_id', 'Company', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$employeeId 		= $this->input->post('emp_id');
		$firstName  		= $this->input->post('first_name', true);
		$lastName      		= $this->input->post('last_name', true);
		$email          	= $this->input->post('email', true);
		$password        	= $this->input->post('password', true);
		$mobileNo       	= $this->input->post('mobile_no', true);
		$genderId     		= $this->input->post('gender_id');
		$maritalStatusId    = $this->input->post('marital_status_id');
		$birthDate 			= $this->input->post('birth_date');
		$age  				= $this->input->post('age', true);
		$hireDate      		= $this->input->post('hire_date', true);
		$status          	= $this->input->post('status', true);
		$type        		= $this->input->post('type', true);
		$designation       	= $this->input->post('designation', true);
		$departmentId       = $this->input->post('department_id', true);
		$address     		= $this->input->post('address');
		$cityId     		= $this->input->post('city_id');
		$postalCode     	= $this->input->post('postal_code');
		$countryId 			= $this->input->post('country_id');
		$salaryId  			= $this->input->post('salary_id', true);
		$accountDetailsId   = $this->input->post('account_details_id', true);
		$roleId          	= $this->input->post('role_id', true);
		$workWeekId        	= $this->input->post('work_week_id', true);
		$companyId       	= $this->input->post('company_id', true);

		$employeeArray = array(
			'emp_id'				=> $employeeId,
			'first_name'			=> $firstName,
			'last_name'				=> $lastName,
			'email'					=> $email,
			'password'				=> $password,
			'mobile_no'				=> $mobileNo,
			'gender_id'				=> $genderId,
			'marital_status_id' 	=> $maritalStatusId,
			'birth_date'			=> DMYToYMD($birthDate),
			'age'					=> $age,
			'hire_date'				=> DMYToYMD($hireDate),
			'status'				=> $status,
			'type'					=> $type,
			'designation'			=> $designation,
			'department_id'			=> $departmentId,
			'address'				=> $address,
			'city_id'				=> $cityId	,
			'postal_code'			=> $postalCode,
			'country_id'			=> $countryId,
			'salary_id'				=> $salaryId,
			'account_details_id'	=> $accountDetailsId,
			'role_id'				=> $roleId,
			'work_week_id'			=> $workWeekId,
			'company_id'			=> $companyId,
			'created_at'    		=> date('Y-m-d h:i:s'),
			'created_by'    		=> $employeeId,
			'updated_at'    		=> date('Y-m-d h:i:s'),
			'updated_by'    		=> $employeeId,
		);
		if ((isset($employeeId) && $employeeId != '')) {
			$successMessage = 'Employee updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($employeeArray['emp_id']);
			$successMessage = 'Employee inserted successfully';
			$errorMessage = 'Post insert unsuccessful';
		}
		$employeeData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($employeeArray, 'emp_id', 'tbl_employee', 1);
		$lastEmployeeId = $employeeData['lastInsertedId'];

		if ($employeeData['success'] && $employeeData['lastInsertedId'] != '') {
			if (isset($_FILES["employee_photo"])) {
				$PostPath       = $this->config->item('employee_image');
				$imageResult    = $this->dt_ci_file_upload->UploadMultipleFile('employee_photo', MAX_IMAGE_SIZE_LIMIT, $PostPath, true, true, array('jpeg', 'png', 'jpg', 'JPG'));

				if ($imageResult['success'] == false) {
					$this->response(array(
						'status' => FALSE,
						'responseCode' => self::HTTP_OK,
						'message' => strip_tags($imageResult['msg']),
					), REST_Controller::HTTP_OK);
				} else {
					unset($imageResult['success']);
					$batchArray = array();
					foreach ($imageResult as $key => $data) {
						$batchArray[] = array(
							'employee_file_id' => '',
							'emp_id' 		   => $lastEmployeeId,
							'filename' 		   => $data['file_name'],
						);
					}
					$this->db->insert_batch('tbl_employee_file', $batchArray);
				}
			}

			$this->db->trans_commit();
			$this->response(array(
				'status' => TRUE,
				'responseCode' => self::HTTP_OK,
				'message' => $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' => FALSE,
				'responseCode' => self::HTTP_NOT_FOUND,
				'message' => $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteEmployee_post()
	{
		$employeeId = $this->input->post('emp_id');
		$this->form_validation->set_rules('emp_id', 'Employee Id', 'required|integer');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => null,
				'responseCode'  => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$employeeImageResult = $this->Mdl_employee_webservice->getImage($employeeId);

		if (isset($employeeImageResult) && !empty($employeeImageResult)) {
			foreach ($employeeImageResult as $employeeImageData) {
				if (isset($employeeImageData) && !empty($employeeImageData)) {
					if (file_exists($this->config->item('employee_image') . $employeeImageData['filename'])) {
						@unlink($this->config->item('employee_image') . $employeeImageData['filename']);
					}
				}
			}
		}
		$employeeData = $this->Mdl_employee->deleteRecord($employeeId);

		if ($employeeData['success']) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Employee deleted successfully',
				'limit'         => DATA_LIMIT,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_NOT_FOUND,
				'message'       => "Business Failed to delete",
				'limit'         => DATA_LIMIT,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function getEmployeeLeaveListing_post()
	{
		$employeeLeaveData 	= $this->Mdl_employee_leave_type->getEmployeeLeaveListing();
		if (!empty($employeeLeaveData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Employee Leave Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $employeeLeaveData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditEmployeeLeave_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('leave_id', 'Leave Id', 'integer');
		$this->form_validation->set_rules('emp_id', 'Employee Id', 'required');
		$this->form_validation->set_rules('leave_type_id', 'Leave Type', 'required');
		$this->form_validation->set_rules('leave_from_date', 'From Date', 'required');
		$this->form_validation->set_rules('leave_to_date', 'To Date', 'required');
		$this->form_validation->set_rules('no_of_days', 'Number of Days', 'required');
		$this->form_validation->set_rules('remaining_leaves', 'Remaining Days', 'required');
		$this->form_validation->set_rules('leave_reason', 'Leave Reason', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => [],
				'responseCode' => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}
		$leaveId   		= $this->input->post('leave_id');
		$empId   		= $this->input->post('emp_id',TRUE);
		$leaveType      = $this->input->post('leave_type_id', true);
		$fromDate      	= $this->input->post('leave_from_date', true);
		$toDate         = $this->input->post('leave_to_date', true);
		$noOfDays       = $this->input->post('no_of_days', true);
		$remainingDays  = $this->input->post('remaining_leaves', true);
		$leaveReason    = $this->input->post('leave_reason', true);

		$employeeLeaveArray = array(
			'leave_id'    		=> $leaveId,
			'emp_id'    		=> $empId,
			'leave_type_id'     => $leaveType,
			'leave_from_date'   => DMYToYMD($fromDate),
			'leave_to_date'     => DMYToYMD($toDate),
			'no_of_days'       	=> $noOfDays,
			'remaining_leaves'  => $remainingDays,
			'leave_reason'      => $leaveReason,
		);
		if ((isset($leaveId) && $leaveId != '')) {
			$successMessage = 'Employee Leave updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($employeeLeaveArray['leave_id']);
			$successMessage = 'Employee Leave inserted successfully';
			$errorMessage = 'Employee Leave insert unsuccessful';
		}
		$employeeLeaveData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($employeeLeaveArray, 'leave_id', 'tbl_employee_leaves', 1);

		if ($employeeLeaveData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' => TRUE,
				'responseCode' => self::HTTP_OK,
				'message' => $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' => FALSE,
				'responseCode' => self::HTTP_NOT_FOUND,
				'message' => $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteEmployeeLeave_post()
	{
		$this->form_validation->set_rules('leave_id', 'Leave Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => [],
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}
		$leaveId 			= $this->input->post('leave_id');
		$employeeLeaveData  = $this->Mdl_employee_leave_type->deleteRecord($leaveId);

		if ($employeeLeaveData['success']) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Employee Leave Deleted successfully',
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "Employee Leave Failed to delete",
			), REST_Controller::HTTP_OK);
		}
	}

	public function getDepartmentListing_post()
	{
		$departmentData = $this->Mdl_department->getDepartmentListing();
		if (!empty($departmentData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode' 	=> self::HTTP_OK,
				"message"       => 'Department Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $departmentData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				'limit'        => DATA_LIMIT,
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditDepartment_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('department_id', 'Department Id', 'integer');
		$this->form_validation->set_rules('dept_name', 'Department Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$departmentId   	= $this->input->post('department_id');
		$departmentName   	= $this->input->post('dept_name');
		$description    	= $this->input->post('description', true);

		$departmentArray = array(
			'department_id' => $departmentId,
			'dept_name'    	=> $departmentName,
			'description'  	=> $description,
		);
		if ((isset($departmentId) && $departmentId != '')) {
			$successMessage = 'Department updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($departmentArray['department_id']);
			$successMessage = 'Department inserted successfully';
			$errorMessage = 'Department insert unsuccessful';
		}
		$departmentData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($departmentArray, 'department_id', 'tbl_department', 1);

		if ($departmentData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'  => self::HTTP_OK,
				'message'	 	=> $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' 		=> FALSE,
				'responseCode' 	=> self::HTTP_NOT_FOUND,
				'message' 		=> $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteDepartment_post()
	{
		$departmentId   	= $this->input->post('department_id');
		$this->form_validation->set_rules('department_id', 'Department Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => [],
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}

		
		$departmentData     = $this->Mdl_department->deleteRecord($departmentId);
		

		if ($departmentData['success'] == "true") {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Department Deleted successfully',
				'data'          => $departmentData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "Department Failed to delete",
			), REST_Controller::HTTP_OK);
		}
	}

	public function getRoleListing_post()
	{
		$roleData = $this->Mdl_role->getRoleListing();
		if (!empty($roleData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode' 	=> self::HTTP_OK,
				"message"       => 'Role Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $roleData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				'limit'        => DATA_LIMIT,
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditRole_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('role_id', 'Role Id', 'integer');
		$this->form_validation->set_rules('title', 'Title', 'required');
		$this->form_validation->set_rules('role', 'Role', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$roleId   		= $this->input->post('role_id');
		$title   		= $this->input->post('title');
		$role    		= $this->input->post('role', true);
		$description    = $this->input->post('description', true);

		$roleArray = array(
			'role_id' 		=> $roleId,
			'title'    		=> $title,
			'role'  		=> $role,
			'description'  	=> $description,
		);
		if ((isset($roleId) && $roleId != '')) {
			$successMessage = 'Role updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($roleArray['role_id']);
			$successMessage = 'Role inserted successfully';
			$errorMessage = 'Role insert unsuccessful';
		}
		$roleData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($roleArray, 'role_id', 'tbl_role', 1);

		if ($roleData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'  => self::HTTP_OK,
				'message'	 	=> $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' 		=> FALSE,
				'responseCode' 	=> self::HTTP_NOT_FOUND,
				'message' 		=> $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteRole_post()
	{
		$roleId    = $this->input->post('role_id');
		$this->form_validation->set_rules('role_id', 'Role Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => [],
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}

		
		$roleData  = $this->Mdl_role->deleteRecord($roleId);

		if ($roleData['success']) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Role Deleted successfully',
				'data'          => $roleData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "Role Failed to delete",T,
			), REST_Controller::HTTP_OK);
		}
	}

	public function getCompanyListing_post()
	{
		$companyData = $this->Mdl_company->getCompanyListing();
		if (!empty($companyData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode' 	=> self::HTTP_OK,
				"message"       => 'Company Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $companyData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				'limit'        => DATA_LIMIT,
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditCompany_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('company_id', 'Company Id', 'integer');
		$this->form_validation->set_rules('company_name', 'Company Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('city_id', 'City', 'required');
		$this->form_validation->set_rules('postal_code', 'Postal Code', 'required');
		$this->form_validation->set_rules('country_id', 'Country', 'required');
		$this->form_validation->set_rules('status', 'Status', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$companyId   		= $this->input->post('company_id');
		$companyName   		= $this->input->post('company_name');
		$description    	= $this->input->post('description', true);
		$address    		= $this->input->post('address', true);
		$cityId    			= $this->input->post('city_id', true);
		$postalCode    		= $this->input->post('postal_code', true);
		$countryId   		= $this->input->post('country_id', true);
		$status  			= $this->input->post('status', true);

		$companyArray = array(
			'company_id' 	=> $companyId,
			'company_name'  => $companyName,
			'description'  	=> $description,
			'address'  		=> $address,
			'city_id'  		=> $cityId,
			'postal_code'  	=> $postalCode,
			'country_id'  	=> $countryId,
			'status'  		=> $status,
		);
		if ((isset($companyId) && $companyId != '')) {
			$successMessage = 'Company updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($companyArray['company_id']);
			$successMessage = 'Company inserted successfully';
			$errorMessage = 'Company insert unsuccessful';
		}
		$companyData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($companyArray, 'company_id', 'tbl_companies', 1);

		if ($companyData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'  => self::HTTP_OK,
				'message'	 	=> $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' 		=> FALSE,
				'responseCode' 	=> self::HTTP_NOT_FOUND,
				'message' 		=> $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteCompany_post()
	{
		$this->form_validation->set_rules('company_id', 'Company Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => [],
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}

		$companyId    = $this->input->post('company_id');
		$companyData  = $this->Mdl_company->deleteRecord($companyId);

		if ($companyData['success'] == "true") {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Companies Deleted successfully',
				'data'          => $companyData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "Companies Failed to delete",
			), REST_Controller::HTTP_OK);
		}
	}

	public function getSalaryListing_post()
	{
		$salaryData = $this->Mdl_salary->getSalaryListing();
		if (!empty($salaryData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode' 	=> self::HTTP_OK,
				"message"       => 'Salary Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $salaryData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				'limit'        => DATA_LIMIT,
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditSalary_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('salary_id', 'Salary Id', 'integer');
		$this->form_validation->set_rules('amount', 'Amount', 'required');
		$this->form_validation->set_rules('esic', 'ESIC', 'required');
		$this->form_validation->set_rules('pf', 'PF', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$salaryId   = $this->input->post('salary_id');
		$amount   	= $this->input->post('amount');
		$esic   	= $this->input->post('esic', true);
		$pf     	= $this->input->post('pf', true);

		$salaryArray = array(
			'salary_id' => $salaryId,
			'amount'    => $amount,
			'esic'  	=> $esic,
			'pf'  		=> $pf,
		);
		if ((isset($salaryId) && $salaryId != '')) {
			$successMessage = 'Salary updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($salaryArray['salary_id']);
			$successMessage = 'Salary inserted successfully';
			$errorMessage = 'Salary insert unsuccessful';
		}
		$salaryData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($salaryArray, 'salary_id', 'tbl_salary', 1);

		if ($salaryData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'  => self::HTTP_OK,
				'message'	 	=> $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' 		=> FALSE,
				'responseCode' 	=> self::HTTP_NOT_FOUND,
				'message' 		=> $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteSalary_post()
	{
		$salaryId   = $this->input->post('salary_id');
		$this->form_validation->set_rules('salary_id', 'Salary Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => [],
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}

		
		$salaryData = $this->Mdl_salary->deleteRecord($salaryId);

		if ($salaryData['success'] == "true") {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Salary Deleted successfully',
				'data'          => $salaryData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "Salary Failed to delete",
			), REST_Controller::HTTP_OK);
		}
	}

	
public function getGenderListing_post()
	{
		$genderData = $this->Mdl_gender->getGenderListing();
		if (!empty($genderData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode' 	=> self::HTTP_OK,
				"message"       => 'Gender Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $genderData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				'limit'        => DATA_LIMIT,
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditGender_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('gender_id', 'Gender Id', 'integer');
		$this->form_validation->set_rules('gender_name', 'Gender Name', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$genderId   = $this->input->post('gender_id');
		$genderName = $this->input->post('gender_name');

		$genderArray = array(
			'gender_id'    => $genderId,
			'gender_name'  => $genderName,
		);
		if ((isset($genderId) && $genderId != '')) {
			$successMessage = 'Gender updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($genderArray['gender_id']);
			$successMessage = 'Gender inserted successfully';
			$errorMessage = 'Gender insert unsuccessful';
		}
		$genderData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($genderArray, 'gender_id', 'tbl_gender', 1);

		if ($genderData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'  => self::HTTP_OK,
				'message'	 	=> $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' 		=> FALSE,
				'responseCode' 	=> self::HTTP_NOT_FOUND,
				'message' 		=> $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteGender_post()
	{
		$this->form_validation->set_rules('gender_id', 'Gender Id', 'required|integer');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => null,
				'responseCode'  => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}

		$genderId   = $this->input->post('gender_id');
		$genderData = $this->Mdl_gender->deleteRecord($genderId);

		if ($genderData['success']) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Gender Deleted successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $genderData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_NOT_FOUND,
				'message'       => "Gender Failed to delete",
				'limit'         => DATA_LIMIT,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function getMaritalStatusListing_post()
	{
		$maritalStatusData = $this->Mdl_marital_status->getMaritalStatusListing();
		echo "api"; print_r($maritalStatusData); die;
		if (!empty($maritalStatusData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode' 	=> self::HTTP_OK,
				"message"       => 'Marital Status Listing successfully',
				'data'          => $maritalStatusData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditMaritalStatus_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('marital_status_id', 'Marital Status Id', 'integer');
		$this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$maritalStatusId   = $this->input->post('marital_status_id');
		$maritalStatus     = $this->input->post('marital_status');

		$maritalStatusArray = array(
			'marital_status_id' => $maritalStatusId,
			'marital_status'    => $maritalStatus,
		);
		if ((isset($maritalStatusId) && $maritalStatusId != '')) {
			$successMessage = 'Marital Status updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($maritalStatusArray['marital_status_id']);
			$successMessage = 'Marital Status inserted successfully';
			$errorMessage = 'Marital Status insert unsuccessful';
		}
		$maritalStatusData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($maritalStatusArray, 'marital_status_id', 'tbl_marital_status', 1);

		if ($maritalStatusData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'  => self::HTTP_OK,
				'message'	 	=> $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' 		=> FALSE,
				'responseCode' 	=> self::HTTP_NOT_FOUND,
				'message' 		=> $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteMaritalStatus_post()
	{
		$this->form_validation->set_rules('marital_status_id', 'Marital Status Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => [],
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}

		$maritalStatusId   = $this->input->post('marital_status_id');
		$maritalStatusData = $this->Mdl_marital_status->deleteRecord($maritalStatusId);

		if ($maritalStatusData['success']) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Marital Status Deleted successfully',
				'data'          => $maritalStatusData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "Marital Status Failed to delete",
			), REST_Controller::HTTP_OK);
		}
	}

	public function getLeaveTypeListing_post()
	{
		$leaveTypeData = $this->Mdl_leave_type->getLeaveTypeListing();
		if (!empty($leaveTypeData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode' 	=> self::HTTP_OK,
				"message"       => 'Leave Type Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $leaveTypeData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				'limit'        => DATA_LIMIT,
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}

	public function addEditLeaveType_post()
	{
		$this->db->trans_begin();
		$this->form_validation->set_rules('leave_type_id', 'Leave Type Id', 'integer');
		$this->form_validation->set_rules('leave_type', 'Leave Type', 'required');
		$this->form_validation->set_rules('no_of_days', 'Leave Type', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_message('required', '%s is required');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status" => FALSE,
				"message" => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data" => null,
				'responseCode' => self::HTTP_BAD_REQUEST,
			), REST_Controller::HTTP_BAD_REQUEST);
		}
		$leaveTypeId     = $this->input->post('leave_type_id');
		$leaveType       = $this->input->post('leave_type');
		$noOfDays        = $this->input->post('no_of_days');
		$description     = $this->input->post('leave_type');

		$leaveTypeArray = array(
			'leave_type_id' => $leaveTypeId,
			'leave_type'    => $leaveType,
			'no_of_days'    => $noOfDays,
			'description'   => $description,
		);
		if ((isset($leaveTypeId) && $leaveTypeId != '')) {
			$successMessage = 'Leave Type updated successfully';
			$errorMessage = 'No data found';
		} else {
			unset($leaveTypeArray['marital_status_id']);
			$successMessage = 'Leave Type inserted successfully';
			$errorMessage = 'Leave Type insert unsuccessful';
		}
		$leaveTypeData   = $this->Mdl_employee_webservice->insertUpdateRecordApi($leaveTypeArray, 'leave_type_id', 'tbl_leave_type', 1);

		if ($leaveTypeData['success']) {
			$this->db->trans_commit();
			$this->response(array(
				'status' 		=> TRUE,
				'responseCode'  => self::HTTP_OK,
				'message'	 	=> $successMessage,
			), REST_Controller::HTTP_OK);

		} else {
			$this->db->trans_rollback();
			$this->response(array(
				'status' 		=> FALSE,
				'responseCode' 	=> self::HTTP_NOT_FOUND,
				'message' 		=> $errorMessage,
			), REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function deleteLeaveType_post()
	{
		$leaveTypeId   = $this->input->post('leave_type_id');
		$this->form_validation->set_rules('leave_type_id', 'Leave Type Id', 'required');
		$this->form_validation->set_message('required', '%s is required');
		$this->form_validation->set_message('integer', '%s should be integer');
		if ($this->form_validation->run() === FALSE) {
			$strip_message = strip_tags(validation_errors(""));
			$this->response(array(
				"status"        => FALSE,
				"message"       => trim(preg_replace("/\r\n|\r|\n/", ',', $strip_message), ","),
				"data"          => [],
				'responseCode'  => self::HTTP_OK,
			), REST_Controller::HTTP_OK);
		}

		
		$leaveTypeData = $this->Mdl_leave_type->deleteRecord($leaveTypeId);
		

		if ($leaveTypeData['success'] == "true") {
			$this->response(array(
				'status'        => TRUE,
				'responseCode'  => self::HTTP_OK,
				"message"       => 'Leave Type Deleted successfully',
				'data'          => $leaveTypeData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"        => FALSE,
				'responseCode'  => self::HTTP_OK,
				'message'       => "Leave Type Failed to delete",
			), REST_Controller::HTTP_OK);
		}
	}
	
	public function getWorkWeekListing_post()
	{
		$workWeekData = $this->Mdl_work_week->getWorkWeekListing();

		if (!empty($workWeekData)) {
			$this->response(array(
				'status'        => TRUE,
				'responseCode' 	=> self::HTTP_OK,
				"message"       => 'Work Week Listing successfully',
				'limit'         => DATA_LIMIT,
				'data'          => $workWeekData,
			), REST_Controller::HTTP_OK);
		} else {
			$this->response(array(
				"status"       => FALSE,
				'responseCode' => self::HTTP_OK,
				'message'      => "No Data Found",
				'limit'        => DATA_LIMIT,
				"data"         => [],
			), REST_Controller::HTTP_OK);
		}
	}
}
