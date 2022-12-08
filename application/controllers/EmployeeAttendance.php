<?php


class EmployeeAttendance extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_employee_attendance","Mdl_employee","Mdl_team_management"));
		$this->lang->load('employee_attendance');
	}

	//Index page
	public function index($empId='')
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js",
		);

		if($empId != ""){
			$data['empId'] = $empId;
		}
		$data['empName']  			= $this->Mdl_employee_attendance->getTotalEmployee();

		if($this->dt_ci_acl->checkAccess('EmployeeAttendance|index')){
			$data['bulk_upload_url'] = base_url('EmployeeAttendance/ajaxUploadFile');
			$data['bulk_upload_sample_download'] = array(
				"Employee Attendance" => base_url('download/sample_upload/sample_employee.csv')
			);
			$data['supporting_views'] = array('v_bulk_upload',"v_common_js", "v_select2");
			$this->dt_ci_template->load('default', "employeeAttendance/v_employee_attendance", $data);
		}

	}
	public function attendanceCorrection()
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js",

		);

		$this->dt_ci_template->load("default", "employeeAttendance/v_admin_attendance_correction_list", $data);
	}
	
	public function correctionList($emp_id ='')
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js",

		);
		if($emp_id != "")
			$data['empId'] = $emp_id;

		$this->dt_ci_template->load("default", "employeeAttendance/v_employee_attendance_correction_list", $data);
	}
	public function adminCorrectionList()
	{
		$startDate = $this->input->post('startDate',TRUE);
		$endDate   = $this->input->post('endDate',TRUE);

		$this->load->library('datatables');
		$this->datatables->select("tac.attendance_correction_id,tac.employee_attendance_id,tac.approved,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name, 
		date_format(tac.login_time,'".PHP_TIME_MYSQL_FORMAT."') as login_time,
        date_format(tac.logout_time,'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
        date_format(tac.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date");
		$this->datatables->from("tbl_attendance_correction as tac");
		$this->datatables->where('approved != ','true');
		$this->datatables->where('rejected != ','true');
		$this->datatables->join('tbl_employee as te','te.emp_id = tac.emp_id','left');

		if($startDate != ''){
			$this->datatables->where('tac.attendance_date >=', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->datatables->where('tac.attendance_date <=', DMYToYMD($endDate));
		}

		echo $this->datatables->generate();
	}

	public function employeeCorrectionList()
	{
		$startDate = $this->input->post('startDate',TRUE);
		$endDate   = $this->input->post('endDate',TRUE);

		$this->load->library('datatables');
		$this->datatables->select("tac.attendance_correction_id,tac.employee_attendance_id,te.emp_id,
		CONCAT(tac.rejected,'|',tac.approved) as status,
		CONCAT(te.first_name,' ',te.last_name) as emp_name, 
		date_format(tac.login_time,'".PHP_TIME_MYSQL_FORMAT."') as login_time,
        date_format(tac.logout_time,'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
        date_format(tac.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date");
		$this->datatables->from("tbl_attendance_correction as tac");
		
		if(isset($_POST['empId']) && $_POST['empId'] != "")
			$this->datatables->where('tac.emp_id',$_POST['empId']);
		
		$this->datatables->join('tbl_employee as te','te.emp_id = tac.emp_id','left');

		if($startDate != ''){
			$this->datatables->where('tac.attendance_date >=', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->datatables->where('tac.attendance_date <=', DMYToYMD($endDate));
		}

		echo $this->datatables->generate();
	}
	
	public function attendanceCorrectionManage($empId = '',$correctionId=''){
		if($empId != '' && $correctionId !=''){
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
					'employeeName'   => true,
				);
				$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);
					
				$data['getEmployeeAttendanceData'] = $this->Mdl_employee_attendance->getEmployeeAttendanceCorrectionData($correctionId);
			
				$this->dt_ci_template->load("default", "employeeAttendance/v_employee_attendance_correction_manage", $data);
					
		}
	}
	
	public function attendanceDetails($empId = '',$attendaceDate='')
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js",

		);
		if($empId != "" && $attendaceDate != ""){
			$data['empId'] = $empId;
			$data['attendaceDate'] = $attendaceDate;
			$data['getEmployeeAttendanceDetails'] = $this->Mdl_employee_attendance->getAttendanceDetails($empId,$attendaceDate);
		}
		
		$this->dt_ci_template->load("default", "employeeAttendance/v_employee_attendance_details", $data);
	}
	
	

	// ajax call to the data listing
	public function getEmployeeAttendanceListing()
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
		if($empId !=  ""){		
			$this->datatables->select("tea.employee_attendance_id,te.emp_id,(CONCAT(te.first_name,' ',te.last_name)) as emp_name, 
			(case when te.emp_id = ".$empId." then 0 else te.emp_id end) as loginEmp,
			te.email,tr.role,max(tea.logout_time) as out_time,min(tea.logout_time) as min_out_time,
			date_format(min(tea.login_time),'".PHP_TIME_MYSQL_FORMAT."') as login_time,
			date_format(max(tea.logout_time),'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
			SEC_TO_TIME(sum(TIME_TO_SEC(TIMEDIFF(tea.logout_time,tea.login_time)))) as total_time,
			date_format(tea.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date");
		}else{
			$this->datatables->select("tea.employee_attendance_id,te.emp_id,(CONCAT(te.first_name,' ',te.last_name)) as emp_name, 
			te.email,tr.role,max(tea.logout_time) as out_time,min(tea.logout_time) as min_out_time,
			date_format(min(tea.login_time),'".PHP_TIME_MYSQL_FORMAT."') as login_time,
			date_format(max(tea.logout_time),'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
			SEC_TO_TIME(sum(TIME_TO_SEC(TIMEDIFF(tea.logout_time,tea.login_time)))) as total_time,
			date_format(tea.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date");
		}
		if($startDate != ''){
			$this->datatables->where('tea.attendance_date >=', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->datatables->where('tea.attendance_date <=', DMYToYMD($endDate));
		}
		
		if(!empty($empIds)){
			$this->datatables->where_in('te.emp_id', $empIds );
			if($empId != '' ){
				$this->db->order_by('loginEmp', 'ASC');
			}
		}
		$this->datatables->from("tbl_employee_attendance as tea");
		$this->datatables->where('LOWER(tr.role) !=', 'admin' );
		$this->datatables->group_by('tea.emp_id,attendance_date');
		$this->datatables->join('tbl_employee as te','te.emp_id = tea.emp_id','left');
		$this->datatables->join('tbl_role as tr','te.role_id = tr.role_id','left');
		echo $this->datatables->generate();
	}

	//insert and update function
	public function manage($employeeAttendanceId = '',$attendanceDate='') // change here manage
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
			'employeeName'   => true,
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);
		
		if($employeeAttendanceId != '' && $attendanceDate == '') {
			$data['getEmployeeAttendanceData'] = $this->Mdl_employee_attendance->getEmployeeAttendanceData($employeeAttendanceId);

		}else if($employeeAttendanceId != '' && $attendanceDate != '') {
			$empName 						   = $this->Mdl_employee_attendance->getEmployeeName($employeeAttendanceId);
			$data['getEmployeeAttendanceData'] = array('emp_id' => $employeeAttendanceId, 'attendance_date' => $attendanceDate, 'emp_name' => $empName['emp_name']);

		}
		$this->dt_ci_template->load("default", "employeeAttendance/v_employee_attendance_manage", $data);
	}
	
	public function getAttendanceCorrectionData($empId='',$correctionId = ''){
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
		if($correctionId != ""){
			$data['correctionData'] = $this->Mdl_employee_attendance->getEmployeeAttendanceCorrectionData($correctionId);
			$this->dt_ci_template->load("default", "employeeAttendance/v_employee_attendance_correction_data", $data);
		}
	}
	
	
	public function correction($employeeId = '',$attendanceId='') // change here manage
	{
		if($employeeId != '' && $attendanceId != '') {
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
				'employeeName'   => true,
			);
			$data['select2'] 				   = $this->load->view("commonMaster/v_select2",$select2,true);
			$data['getEmployeeAttendanceData'] = $this->Mdl_employee_attendance->getEmployeeAttendanceData($attendanceId);
			$data['correction'] 			   = 'true';
			
			$this->dt_ci_template->load("default", "employeeAttendance/v_employee_attendance_manage", $data);
		}
	}

	public function viewEmpList()
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js"
		);

		$this->dt_ci_template->load("default", "employeeAttendance/v_employee_attendance_data", $data);
	}

	public function getEmployeeAttendanceDataListing() // change here manage
	{
		$empId 			= $this->input->post('empId',TRUE);
		$attendanceDate = $this->input->post('attendaceDate',TRUE);

		$this->load->library('datatables');
		$this->datatables->select("tea.employee_attendance_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name,
		tr.role,tea.logout_time as out_time,
		date_format(tea.login_time,'".PHP_TIME_MYSQL_FORMAT."') as login_time,
        date_format(tea.logout_time,'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
		TIMEDIFF(tea.logout_time,tea.login_time) as total_time,
        date_format(tea.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date");

		$this->datatables->from("tbl_employee_attendance as tea");
		$this->datatables->where('tea.attendance_date',DMYToYMD($attendanceDate));
		$this->datatables->where('tea.emp_id',$empId);
		$this->datatables->join('tbl_employee as te','tea.emp_id = te.emp_id');
		$this->datatables->join('tbl_role as tr','te.role_id = tr.role_id','left');

		$allData = json_decode($this->datatables->generate());
		$results = $allData->data;
		$i 		 = 0;
		foreach($results as $result){
			$id 							= $result->employee_attendance_id;
			$result 						= (array)$result;
			$correctionData 				= $this->Mdl_employee_attendance->getCorrectionStatus($id);
			$result['correction_id'] 		= $correctionData[0];
			$result['correction_status'] 	= $correctionData[1];
			$result 						= (object)$result;
			$results[$i] 					= $result;
			$i++;
		}
		
		$datatable = array(
			'draw' 				=> 	$allData->draw,
			'recordsTotal' 		=> 	$allData->recordsTotal,
			'recordsFiltered'	=>	$allData->recordsFiltered,
			'data' 				=> 	$results
		);
		echo json_encode($datatable); 
	}


	// Save function here
	public function save()
	{
		$employeeAttendanceId     = $this->input->post('employee_attendance_id');
		$employeeName   		  = $this->input->post('emp_active_id', TRUE);
		$loginTime   			  = $this->input->post('login_time', TRUE);
		$logoutTime     	   	  = $this->input->post('logout_time', TRUE);
		$attendanceDate  		  = $this->input->post('attendance_active_date', TRUE);

		$this->form_validation->set_rules('emp_active_id', $this->lang->line('employee_name'), 'required');
		$this->form_validation->set_rules('attendance_active_date', $this->lang->line('attendance_date'), 'required');
		$this->form_validation->set_rules('login_time', $this->lang->line('login_time'), 'required');
		$this->form_validation->set_rules('logout_time', $this->lang->line('logout_time'), 'required');

		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			if(isset($loginTime) && isset($logoutTime)) {
				if($loginTime > $logoutTime) {
					$response['success'] = false;
					$response['msg'] = "Logout Time Should not be less than Login time";
					echo json_encode($response);
					exit;
				}
			}
			
			$employeeAttendanceArray = array(
				'employee_attendance_id'  => $employeeAttendanceId,
				'emp_id'  		  		  => $employeeName,
				'login_time'  			  => $loginTime,
				'logout_time'  			  => $logoutTime,
				'attendance_date'  		  => DMYToYMD($attendanceDate),
			);
			

			$employeeAttendanceData  = $this->Mdl_employee_attendance->insertUpdateRecord($employeeAttendanceArray, 'employee_attendance_id', 'tbl_employee_attendance', 1);

			if (isset($employeeAttendanceId) && $employeeAttendanceId != '') {
				if ($employeeAttendanceData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), EMPLOYEEATTENDANCE);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), EMPLOYEEATTENDANCE);
				}
			} else {
				if ($employeeAttendanceData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('create_record'), EMPLOYEEATTENDANCE);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('create_record_error'), EMPLOYEEATTENDANCE);
				}
			}
			echo json_encode($response);
		}
	}
	
	
	
	// Save function here
	public function saveAttendanceCorrection()
	{
		$attendanceCorrectionId     = $this->input->post('attendance_correction_id') ? $this->input->post('attendance_correction_id') : '';
		$employeeAttendanceId       = $this->input->post('employee_attendance_id');
		$employeeName   		  	= $this->input->post('emp_active_id', TRUE);
		$loginTime   			  	= $this->input->post('login_time', TRUE);
		$logoutTime     	   	  	= $this->input->post('logout_time', TRUE);
		$attendanceDate  		  	= $this->input->post('attendance_active_date', TRUE);
		$approved				  	= $this->input->post('approved', TRUE) ? 'true' : 'false';
		$rejected				  	= $this->input->post('rejected', TRUE) ? 'true' : 'false';
		$note 					  	= $this->input->post('note', TRUE);
		
		
		$this->form_validation->set_rules('emp_active_id', $this->lang->line('employee_name'), 'required');
		$this->form_validation->set_rules('attendance_active_date', $this->lang->line('attendance_date'), 'required');
		$this->form_validation->set_rules('login_time', $this->lang->line('login_time'), 'required');
		$this->form_validation->set_rules('logout_time', $this->lang->line('logout_time'), 'required');
		
		if($rejected == 'true'){
			$this->form_validation->set_rules('note', $this->lang->line('logout_time'), 'required');
		}
		if (isset($attendanceCorrectionId) && $attendanceCorrectionId == '') {
			$this->form_validation->set_rules('employee_attendance_id', $this->lang->line('employee_attendance_id'), 'required|is_unique[tbl_attendance_correction.employee_attendance_id]');
		}
		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			if(isset($loginTime) && isset($logoutTime)) {
				if($loginTime > $logoutTime) {
					$response['success'] = false;
					$response['msg'] = "Logout Time Should not be less than Login time";
					echo json_encode($response);
					exit;
				}
			}
			
			$attendanceCorrectionArray = array(
				'attendance_correction_id' => $attendanceCorrectionId,
				'employee_attendance_id'   => $employeeAttendanceId,
				'emp_id'  		  		   => $employeeName,
				'login_time'  			   => $loginTime,
				'logout_time'  			   => $logoutTime,
				'attendance_date'  		   => DMYToYMD($attendanceDate),
				'approved'				   => $approved,
				'rejected'				   => $rejected,
				'note'					   => $note
			);
			

			$attendanceCorrectionData  = $this->Mdl_employee_attendance->insertUpdateRecord($attendanceCorrectionArray, 'attendance_correction_id', 'tbl_attendance_correction', 1);

			if($approved == 'true' && $rejected == 'false'){
				
				$employeeAttendanceArray = array(
					'employee_attendance_id'  => $employeeAttendanceId,
					'emp_id'  		  		  => $employeeName,
					'login_time'  			  => $loginTime,
					'logout_time'  			  => $logoutTime,
					'attendance_date'  		  => DMYToYMD($attendanceDate),
				);
					
				$employeeAttendanceData  = $this->Mdl_employee_attendance->insertUpdateRecord($employeeAttendanceArray, 'employee_attendance_id', 'tbl_employee_attendance', 1);
			} else{
				$employeeAttendanceData['success'] = 'true';
			}
			
			if (isset($attendanceCorrectionId) && $attendanceCorrectionId != '') {
				if ($attendanceCorrectionData['success'] && $employeeAttendanceData['success']) {
					$dt_ci_email 		  = new dt_ci_email();
					$empDetails 		  = $this->Mdl_employee->getEmployeeEmail($employeeName);
					$oldAttendanceDetails = $this->Mdl_employee_attendance->getOldAttendanceDetails($employeeAttendanceId);
					if($approved == 'true' && $rejected == 'false'){
						 $toEmail = $empDetails['email'];
						 $message = 'Hello <strong>'.$empDetails['emp_name'].'</strong>,<br/><br/>
									Your Attendance Correction request for :<br/>
									Old Login Time : <strong>'.$oldAttendanceDetails['login_time'].'</strong><br/>
									Old Logout Time : <strong>'.$oldAttendanceDetails['logout_time'].'</strong> <br/>
									New Login Time : <strong>'.$loginTime.'</strong><br/> 
									New Logout Time : <strong>'.$logoutTime.'</strong> <br/>
									On Date : <strong>'.$attendanceDate.'</strong> 
									has been approved.<br/><br/>
									
									Thank you,<br/>
									'.FROM_NAME;
					     $status = $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('attendance_correction_approval_subject'), $message);
					}else if($rejected == 'true' && $approved == 'false'){
						 $toEmail = $empDetails['email'];
						 $message = 'Hello <strong>'.$empDetails['emp_name'].'</strong>,<br/><br/>
									Your Attendance Correction request for :<br/>
									Old Login Time : <strong>'.$oldAttendanceDetails['login_time'].'</strong><br/>
									Old Logout Time : <strong>'.$oldAttendanceDetails['logout_time'].'</strong> <br/>
									New Login Time : <strong>'.$loginTime.'</strong><br/> 
									New Logout Time : <strong>'.$logoutTime.'</strong> <br/>
									On Date : <strong>'.$attendanceDate.'</strong>  has been rejected.<br/>
									Reason : <strong>'.$note.'</strong><br/><br/>
									
									Thank you,<br/>
									'.FROM_NAME;
									
					     $status = $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('attendance_correction_rejection_subject'), $message);
					}else if($rejected == 'false' && $approved == 'false'){
						$toEmail 	= FROM_EMAIL;
						 $message 	= 'Hello <strong>'.FROM_NAME.'</strong>,<br/><br/>
										We have got a updated attendance correction request.<br/><br/>
										Details are as below : <br/>
										Name : <strong>'.$empDetails['emp_name'].'</strong> <br/>
										Email Id : <strong>'.$empDetails['email'].'</strong><br/> 
										Old Login Time : <strong>'.$oldAttendanceDetails['login_time'].'</strong><br/>
										Old Logout Time : <strong>'.$oldAttendanceDetails['logout_time'].'</strong><br/>
										New Login Time : <strong>'.$loginTime.'</strong><br/>
										New Logout Time : <strong>'.$logoutTime.'</strong><br/>
										Date : <strong>'.$attendanceDate.'</strong><br/><br/>
									
									Thank you,<br/>
									'.FROM_NAME;
					     $status   = $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('updated_attendance_correction_request_subject'), $message);
					}
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('update_record'), EMPLOYEEATTENDANCECORRECTION);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('update_record_error'), EMPLOYEEATTENDANCECORRECTION);
				}
			} else {
				if ($attendanceCorrectionData['success']) {
					if($approved == 'false' && $rejected == 'false'){
						 $dt_ci_email 			= new dt_ci_email();
						 $empDetails  			= $this->Mdl_employee->getEmployeeEmail($employeeName);
						 $oldAttendanceDetails 	= $this->Mdl_employee_attendance->getOldAttendanceDetails($employeeAttendanceId);
						 $toEmail 				= FROM_EMAIL;
						 $message 				= 'Hello <strong>'.FROM_NAME.'</strong>,<br/><br/>
													We have got a new attendance correction request.<br/><br/>
													Details are as below : <br/>
													Name : <strong>'.$empDetails['emp_name'].'</strong> <br/>
													Email Id : <strong>'.$empDetails['email'].'</strong><br/> 
													Old Login Time : <strong>'.$oldAttendanceDetails['login_time'].'</strong><br/>
													Old Logout Time : <strong>'.$oldAttendanceDetails['logout_time'].'</strong><br/>
													New Login Time : <strong>'.$loginTime.'</strong><br/>
													New Logout Time : <strong>'.$logoutTime.'</strong><br/>
													Date : <strong>'.$attendanceDate.'</strong><br/><br/>
									
												Thank you,<br/>
												'.FROM_NAME;
					     $status 				= $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('new_attendance_correction_request_subject'), $message);
					}
					$response['success'] = true;
					$response['msg']  	 = sprintf($this->lang->line('create_record'), EMPLOYEEATTENDANCECORRECTION);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('create_record_error'), EMPLOYEEATTENDANCECORRECTION);
				}
			}
			echo json_encode($response);
		}
	}


	public function delete()
	{
		$employeeAttendanceId 	= $this->input->post('deleteId',TRUE);
		$employeeAttendanceData = $this->Mdl_employee_attendance->deleteRecord($employeeAttendanceId);

		//delete Employee Attendance
		if ($employeeAttendanceData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),EMPLOYEEATTENDANCE);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),EMPLOYEEATTENDANCE);
		}
		echo json_encode($response);
	}
	public function deleteEmpWiseAttendance()
	{
		$empId 					= $this->input->post('deleteId',TRUE);
		$employeeAttendanceData = $this->Mdl_employee_attendance->deleteEmpAttendanceRecord($empId);

		//deleteEmpWiseAttendance
		if ($employeeAttendanceData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),EMPLOYEEATTENDANCE);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),EMPLOYEEATTENDANCE);
		}
		echo json_encode($response);
	}
	
	
	
	
	
	public function attendanceLogin()
	{  
		$empId = $this->input->post('empId',TRUE);
		$role  = $this->input->post('role',TRUE);
		if(strtolower($role) != 'admin'){
			$attendanceData = array(
				'employee_attendance_id'  => '',
				'emp_id'  		          => $empId,
				'login_time'  			  => date('H:i:s'),
				'logout_time'  			  => '',
				'attendance_date'  		  => date('Y-m-d')
			);
				
			$empData = $this->Mdl_employee_attendance->insertUpdateRecord($attendanceData, 'employee_attendance_id', 'tbl_employee_attendance', 1);
		
			if ($empData['success']) {
				$response['success'] = true;
				$response['msg']     = "Punch In Successfully";
			} else {
				$response['success'] = false;
				$response['msg']     = "Punch In Error";
			}
			echo json_encode($response);
		}
	}
	
	public function attendanceLogout()
	{
		$empId = $this->input->post('empId',TRUE);
		$role  = $this->input->post('role',TRUE);
		if(strtolower($role) != 'admin'){
			$empData = $this->Mdl_employee_attendance->attendanceLogout($empId);
			if ($empData['success']) {
				$response['success'] = true;
				$response['msg']     = "Punch Out Successfully";
			} else {
				$response['success'] = false;
				$response['msg']     = "Punch Out Error";
			}
			echo json_encode($response);
		}
	}

	public function ajaxUploadFile($return = false, $uploadType = 0, $file = array())
	{
		$this->load->library('bulkUploads/EmployeeAttendance_uploads');
		$userId     = $this->ion_auth->get_user_id();
		$dirAbsPath = EMP_ATTEDANCE_PATH;
		$response   = $this->dt_ci_common->uploadFile($_FILES, $dirAbsPath, $file);
		if(isset($response['success']) && $response['success']){
			$data = $this->employeeattendance_uploads->uploadEmployeeAttendance($userId, $return, $file);
			echo json_encode($data);
		}
	}
	
}
