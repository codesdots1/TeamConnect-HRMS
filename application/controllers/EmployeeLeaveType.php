<?php

class EmployeeLeaveType extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Curl');
		$this->load->model(array("Mdl_employee_leave_type","Mdl_employee","Mdl_team_management"));
		$this->lang->load('employee_leave_type');
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
		

		$this->dt_ci_template->load("default", "employeeLeaveType/v_employee_leave_type", $data);
	}

	// ajax call to the data listing
	public function getEmployeeLeaveListing()
	{
		$empId 		= $this->input->post('empId',TRUE);
		$startDate 	= $this->input->post('startDate',TRUE);
		$endDate 	= $this->input->post('endDate',TRUE);
		$role 		= $this->session->userdata('role');

		if(strtolower($role) != 'admin'){
			$loginEmp 	=  $empId;
			$data 		= $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds 	= explode(",",$data['emp_id_listing']);
			array_push($empIds,$loginEmp);
		}

		$this->load->library('datatables');
		if($empId != ""){
			$this->datatables->select("tel.leave_id,concat(te.first_name,' ',te.last_name) as name,tlt.leave_type,
			(case when tel.emp_id = ".$empId." then 0 else tel.emp_id end) as loginEmp,
			date_format(tel.apply_date,'".DATE_FORMATE_MYSQL."') as apply_date,
			date_format(tel.leave_from_date,'".DATE_FORMATE_MYSQL."') as leave_from_date,
			date_format(tel.leave_to_date,'".DATE_FORMATE_MYSQL."') as leave_to_date,
			tel.leave_reason,tel.no_of_days,tel.is_active,tel.is_rejected");
		}else{
			$this->datatables->select("tel.leave_id,concat(te.first_name,' ',te.last_name) as name,tlt.leave_type,
			date_format(tel.apply_date,'".DATE_FORMATE_MYSQL."') as apply_date,
			date_format(tel.leave_from_date,'".DATE_FORMATE_MYSQL."') as leave_from_date,
			date_format(tel.leave_to_date,'".DATE_FORMATE_MYSQL."') as leave_to_date,
			tel.leave_reason,tel.no_of_days,tel.is_active,tel.is_rejected");

		}
		$this->datatables->from("tbl_employee_leaves as tel");
		if($role == 'admin' && $role == 'hr'){
			$this->datatables->where('is_active != ',1);
			$this->datatables->where('is_rejected != ',1);
		}
		if($startDate != ''){
			$this->datatables->where('tel.leave_from_date >=', DMYToYMD($startDate));
			$this->datatables->where('tel.leave_to_date >=', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->datatables->where('tel.leave_from_date <=', DMYToYMD($endDate));
				$this->datatables->where('tel.leave_to_date <=', DMYToYMD($endDate));
		}
		if(!empty($empIds)){
			$this->datatables->where_in('tel.emp_id', $empIds );
			if($empId != '' ){
				$this->db->order_by('loginEmp', 'ASC');
			}
		}
		$this->datatables->join("tbl_leave_type as tlt","tlt.leave_type_id = tel.leave_type_id","left");
		$this->datatables->join("tbl_employee as te","te.emp_id = tel.emp_id","left");
		echo $this->datatables->generate();

	}

	//insert and update function
	public function manage($leaveTypeId = '') // change here manage
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
			"/js/maps/jquery.geocomplete.js",
		);

		$select2 = array(
			'leaveType'  	=> true,
			'employeeName'  => true,
		);
		
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if($leaveTypeId != '') {
			$data['getEmployeeLeaveData'] = $this->Mdl_employee_leave_type->getEmployeeLeaveData($leaveTypeId);
			$empId 						  = $data['getEmployeeLeaveData']['emp_id'];
			$leaveId 					  = $data['getEmployeeLeaveData']['leave_type_id'];
			$data['leaveTypeData'] 		  = $this->Mdl_employee_leave_type->getTotalDays($leaveId,$empId);
		
		} else{
			$empId  						= $this->session->userdata('emp_id');
			$result 						= $this->Mdl_employee->getLoginEmployee($empId);
			$data['getEmployeeLeaveData'] 	= $result;
		}
		$this->dt_ci_template->load("default", "employeeLeaveType/v_employee_leave_type_manage", $data);
	}
	
	
	//insert and update function
	public function getLeaveDataListing($leaveTypeId = '') // change here manage
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
			"/js/maps/jquery.geocomplete.js",
		);

		$select2 = array(
			'leaveType'  	=> true,
			'employeeName'  => true,
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if($leaveTypeId != '') {
			$data['getEmployeeLeaveData'] 	= $this->Mdl_employee_leave_type->getEmployeeLeaveData($leaveTypeId);
			$empId 							= $data['getEmployeeLeaveData']['emp_id'];
			$leaveId 						= $data['getEmployeeLeaveData']['leave_type_id'];
			$data['leaveTypeData'] 			= $this->Mdl_employee_leave_type->getTotalDays($leaveId,$empId);
		}

		$this->dt_ci_template->load("default", "employeeLeaveType/v_employee_leave_type_data", $data);
	}



	// Save function here
	public function save()
	{
		$leaveId      	  = $this->input->post('leave_id');
		$empId      	  = $this->input->post('emp_active_id',  TRUE);
		$leaveType    	  = $this->input->post('leave_type_id', TRUE);
		$fromDate    	  = $this->input->post('leave_from_date', TRUE);
		$toDate    		  = $this->input->post('leave_to_date', TRUE);
		$noOfDays    	  = $this->input->post('no_of_days', TRUE);
		$halfDay          = $this->input->post('half_day', TRUE);
		$leaveReason      = $this->input->post('leave_reason', TRUE);
		
		$approved		  = $this->input->post('approved', TRUE) ? '1' : '0';
		$rejected		  = $this->input->post('rejected', TRUE) ? '1' : '0';
		$note 			  = $this->input->post('note', TRUE);
		
		$this->form_validation->set_rules('emp_active_id', $this->lang->line('employee'), 'required');
		$this->form_validation->set_rules('leave_type_id', $this->lang->line('leave_type'), 'required');
		$this->form_validation->set_rules('leave_from_date', $this->lang->line('leave_from_date'), 'required');
		$this->form_validation->set_rules('leave_to_date', $this->lang->line('leave_to_date'), 'required');
		$this->form_validation->set_rules('no_of_days', $this->lang->line('no_of_days'), 'required');
		$this->form_validation->set_rules('leave_reason', $this->lang->line('leave_reason'), 'required');
		
		if($rejected == '1'){
			$this->form_validation->set_rules('note', $this->lang->line('note'), 'required');
		}
		
		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {

			$employeeLeaveArray = array(
				'leave_id'    		=> $leaveId,
				'emp_id'    		=> $empId,
				'leave_type_id'     => $leaveType,
				'leave_from_date'   => DMYToYMD($fromDate),
				'leave_to_date'     => DMYToYMD($toDate),
				'no_of_days'       	=> $noOfDays,
				'half_day'       	=> $halfDay,
				'leave_reason'      => $leaveReason,
				'is_active'  	    => $approved,
				'is_rejected'		=> $rejected,
				'note'				=> $note
			);

			if (isset($leaveId) && $leaveId == '') {
				$applyDate = date("d-m-Y");
				$employeeLeaveArray['apply_date'] = date("Y-m-d");
			}

			$employeeLeaveData  = $this->Mdl_employee_leave_type->insertUpdateRecord($employeeLeaveArray, 'leave_id', 'tbl_employee_leaves', 1);

			if (isset($leaveId) && $leaveId != '') {
				if ($employeeLeaveData['success']) {
					 $dt_ci_email = new dt_ci_email();
					 $empDetails  = $this->Mdl_employee->getEmployeeEmail($empId);

					if($approved == '1' && $rejected == '0'){
						 $toEmail = $empDetails['email'];
						 $message = 'Hello <strong>'.$empDetails['emp_name'].'</strong>,<br/><br/>
									Your leave from date <strong>'.$fromDate.'</strong> to <strong>'.$toDate.'</strong> has been approved.<br/><br/>
									Thank you,<br/>
									'.FROM_NAME;
					     $status  = $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('leave_approved_subject'), $message);

					} else if($rejected == '1' && $approved == '0'){
						 $toEmail = $empDetails['email'];
						 $message = 'Hello <strong>'.$empDetails['emp_name'].'</strong>,<br/><br/>
									Your leave from date <strong>'.$fromDate.'</strong> to <strong>'.$toDate.'</strong> has been rejected.<br/>
									Reason : <strong>'.$note.'</strong><br/><br/>
									Thank you,<br/>
									'.FROM_NAME;
					     $status  = $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('leave_rejected_subject'), $message);

					} else if($approved == '0' && $rejected == '0'){
						$toEmail 		  = FROM_EMAIL ;
						$leaveTypeDetails = $this->Mdl_employee_leave_type->getEmployeeLeaveData($leaveId);
						$message 		  = 'Hello <strong>'.FROM_NAME.'</strong>,<br/><br/>
												We have got a updated leave request.<br/><br/>
												<strong>Details are as below : </strong><br/>
												Name : <strong>'.$empDetails['emp_name'].'</strong> <br/>
												Email Id : <strong>'.$empDetails['email'].'</strong><br/> 
												Leave Type : <strong>'.$leaveTypeDetails['leave_type'].'</strong><br/>
												From Date : <strong>'.$fromDate.'</strong><br/>
												To Date : <strong>'.$toDate.'</strong><br/>
												Total Days : <strong>'.$noOfDays.'</strong><br/>
												Reason : <strong>'.$leaveReason.'</strong><br/>
												Apply Date : <strong>'.$leaveTypeDetails['apply_date'].'</strong><br/><br/>
											Thank you,<br/>
												'.FROM_NAME;

						$status 		  = $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('updated_leave_request_subject'), $message);
					}
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('update_record'), EMPLOYEELEAVETYPE);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('update_record_error'), EMPLOYEELEAVETYPE);
				}
			} else {
				if($approved == '0' && $rejected == '0'){
					$dt_ci_email 		= new dt_ci_email();
					$empDetails		    = $this->Mdl_employee->getEmployeeEmail($empId);
 					$leaveTypeDetails 	= $this->Mdl_leave_type->getLeaveTypeData($leaveType);
					$toEmail 			= FROM_EMAIL;
					$message 			= 'Hello <strong>'.FROM_NAME.'</strong>,<br/><br/>
												We have got a new leave request.<br/><br/>
												<strong> Details are as below :</strong> <br/>
												Name : <strong>'.$empDetails['emp_name'].'</strong> <br/>
												Email Id : <strong>'.$empDetails['email'].'</strong><br/> 
												Leave Type : <strong>'.$leaveTypeDetails['leave_type'].'</strong><br/>
												From Date : <strong>'.$fromDate.'</strong><br/>
												To Date : <strong>'.$toDate.'</strong><br/>
												Total Days : <strong>'.$noOfDays.'</strong><br/>
												Reason : <strong>'.$leaveReason.'</strong><br/>
												Apply Date : <strong>'.$applyDate.'</strong><br/><br/>
									
											Thank you,<br/>
												'.FROM_NAME;

					$status 			= $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('new_leave_request_subject'), $message);
				}
				if($employeeLeaveData['success']){
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('create_record'), EMPLOYEELEAVETYPE);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('create_record_error'), EMPLOYEELEAVETYPE);
				}
			}
			echo json_encode($response);
		}
	}

	public function changeActive()
	{
		$employeeLeaveTypeId   = $this->input->post('leave_id', TRUE);
		$status    			   = $this->input->post('status', TRUE);
		if ($status == 0) {
			$status = 1;
		} else {
			$status = 0;
		}
		$return = $this->Mdl_employee_leave_type->statusChange($employeeLeaveTypeId, $status, 'leave_id', 'tbl_employee_leaves');
		if ($return == 1) {
			$response['success']  = true;
			$response['msg']      = sprintf($this->lang->line('status_change'), EMPLOYEELEAVETYPE);
		}
		else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('status_change_error'), EMPLOYEELEAVETYPE);
		}
		echo json_encode($response);
	}

	public function delete() {
		$leaveId 			= $this->input->post('deleteId',TRUE);
		$employeeLeaveData  = $this->Mdl_employee_leave_type->deleteRecord($leaveId);

		//delete leave
		if ($employeeLeaveData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),EMPLOYEELEAVETYPE);
		} else {
			$response['success'] = false;
			$response['msg'] 	 =  sprintf($this->lang->line('error_delete_record'),EMPLOYEELEAVETYPE);
		}
		echo json_encode($response);
	}
	
	public function getTotalDays(){
		$leaveTypeId 	= $this->input->post('leaveTypeId',TRUE);
		$empId 		 	= $this->input->post('empId',TRUE);
		$year 			= $this->input->post('year',TRUE);
		$leaveTypeData  = $this->Mdl_employee_leave_type->getTotalDays($leaveTypeId,$empId,$year);
		
		if ($leaveTypeData) {
			$response['success'] 			= true;
			$response['total_leave']     	= $leaveTypeData['total_leave'];
			$response['leave_taken']     	= $leaveTypeData['leave_taken'];
			$response['available_leave']    = $leaveTypeData['available_leave'];
			$response['available_days']     = $leaveTypeData['available_days'];
		} else {
			$response['success'] = false;
			$response['msg'] 	 =  "This is error";
		}

		echo json_encode($response);
	}
}

