<?php


class TimeSheet extends DT_CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model(array("Mdl_time_sheet","Mdl_employee","Mdl_holiday_calendar"));
		$this->lang->load('time_sheet');
	}

	//Index page
	public function index($empId='')
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

		$timeDetails = $this->checkLastDayTotalTime();
		if(!empty($timeDetails)){
			$data['timeDetails'] = $timeDetails;
		}
		$data['empName'] = $this->Mdl_time_sheet->getTotalEmployee();


		$this->dt_ci_template->load("default", "timeSheet/v_time_sheet", $data);
	}

	// ajax call to the data listing
	public function getTimeSheetListing()
	{
		$this->load->library('datatables');
		$this->datatables->select("tts.time_sheet_id,te.emp_id,
		CONCAT(te.first_name,' ',te.last_name) as emp_name,
		TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(tts.hours))),'%H : %i') as hours,
		date_format(tts.time_sheet_date,'" . DATE_FORMATE_MYSQL . "') as time_sheet_date");
		$this->datatables->from("tbl_time_sheet as tts");

		if(isset($_POST['empId']) && $_POST['empId'] != "" && strtolower($this->session->userdata('role')) != 'admin'){
			$this->datatables->where('tts.emp_id',$_POST['empId']);
		}

		$this->datatables->join('tbl_employee as te','te.emp_id = tts.emp_id','left');
		$this->datatables->join("tbl_project as tp","tts.project_id = tp.project_id","left");
		$this->datatables->join("tbl_task as tt","tts.task_id = tt.task_id","left");
		$this->datatables->group_by("tts.emp_id,tts.time_sheet_date",'desc');
		echo $this->datatables->generate();
	}

	public function getTimeSheetDetails($empId = '',$timeSheetDate='')
	{
		$data['extra_js'] = array(
			"js/plugins/tables/datatables/datatables.min.js",
			"js/plugins/notifications/sweet_alert.min.js",
			"js/plugins/forms/styling/uniform.min.js",
			"js/plugins/forms/jquery.form.min.js",
		);

		if($empId != "" && $timeSheetDate != ""){
			$data['empId'] 					= $empId;
			$data['timeSheetDate'] 			= $timeSheetDate;
			$data['getTimeSheetDetails'] 	= $this->Mdl_time_sheet->getTimeSheetDetails($empId,$timeSheetDate);
		}
		
		$timeDetails = $this->checkLastDayTotalTime();
		if(!empty($timeDetails)){
			$data['timeDetails'] = $timeDetails;
		}

		$this->dt_ci_template->load("default", "timeSheet/v_time_sheet_details", $data);
	}

	public function getTimeSheetDataListing() // change here manage
	{
		$empId 		   = $this->input->post('empId',TRUE);
		$timeSheetDate = $this->input->post('timeSheetDate',TRUE);

		$this->load->library('datatables');
		$this->datatables->select("tts.time_sheet_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name,
		tp.project_id,tp.project_name,ts.task_id,ts.task_name,tr.role,tts.hours,tts.note,tl.leave_reason_id,tl.leave_reason_name,
        date_format(tts.time_sheet_date,'".DATE_FORMATE_MYSQL."') as time_sheet_date");

		$this->datatables->from("tbl_time_sheet as tts");
		$this->datatables->where('tts.time_sheet_date',DMYToYMD($timeSheetDate));
		$this->datatables->where('tts.emp_id',$empId);
		$this->datatables->join('tbl_employee as te','tts.emp_id = te.emp_id');
		$this->datatables->join('tbl_project as tp','tp.project_id = tts.project_id','left');
		$this->datatables->join('tbl_leave_reason as tl','tl.leave_reason_id = tts.leave_reason_id','left');
		$this->datatables->join('tbl_task as ts','ts.task_id = tts.task_id','left');
		$this->datatables->join('tbl_role as tr','te.role_id = tr.role_id','left');

		$allData = json_decode($this->datatables->generate());
		$results = $allData->data;
		$datatable = array(
			'draw' 				=> 	$allData->draw,
			'recordsTotal' 		=> 	$allData->recordsTotal,
			'recordsFiltered'	=>	$allData->recordsFiltered,
			'data' 				=> 	$results
		);
		echo json_encode($datatable);
	}


	//insert and update function
	public function manage($timeSheetId = '') // change here manage
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
			'project'  => true,
			'task'     => true,
		);

		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if($timeSheetId != '') {
			$data['getTimeSheetData'] = $this->Mdl_time_sheet->getTimeSheetData($timeSheetId);

			if(empty($data['getTimeSheetData'])){
				$timeDetails = $this->checkLastDayTotalTime();
				if(!empty($timeDetails)){
					$data['timeDetails'] = $timeDetails;
					//printArray($data['timeDetails'],1);
				}
			}
		} else{
			$timeDetails = $this->checkLastDayTotalTime();
			if(!empty($timeDetails)){
				$data['timeDetails'] = $timeDetails; 
			}
		}

		$data['workWeekState'] = $this->Mdl_time_sheet->getSaturdayInfo();

		$this->dt_ci_template->load("default", "timeSheet/v_time_sheet_manage", $data);
	}

	public function checkLastDayTotalTime(){
		if($this->session->userdata('role') != 'admin'){
			$today   	 = date("Y-m-d");
			$empData 	 = $this->Mdl_employee->getEmployeeData($this->session->userdata('emp_id'));
			$workingDays = explode(",",$empData['days_name']);
			$exit 		 = 'false';
			$pre 		 = date('d-m-Y', strtotime($today .' -1 day'));

			while($exit == 'false'){
				$day = date('D', strtotime($pre));
				if(in_array($day,$workingDays)){
					$holiday = $this->Mdl_holiday_calendar->holidayDate($pre);
					if(empty($holiday)){
						$exit = 'true';
					} else{
						$exit = 'false';
						$pre  = date('d-m-Y', strtotime($pre .' -1 day'));
					}
				} else{
					$exit = 'false';
					$pre  = date('d-m-Y', strtotime($pre .' -1 day'));
				}
			}

			$timeSheetDetails = $this->Mdl_time_sheet->getTimeSheetDetails($this->session->userdata('emp_id'),$pre);

			if(empty($timeSheetDetails)){
				$totalTime = 0;
			} else{
				$totalTime = $timeSheetDetails['total_time'];
			}
			$time 	 			= '08:00:00';
			$Halftime 	 		= '04:00:00';
			$WorkingHours 		= strtotime("1970-01-01 $time UTC");
			$workingHalfHours 	= strtotime("1970-01-01 $Halftime UTC");
			$WorkedHours  		= strtotime("1970-01-01 $totalTime UTC");
			$timeArray 			= array();

			if($WorkedHours < $WorkingHours) {
				$remainingTime 	= $WorkingHours - $WorkedHours;
				$fillTime 		= gmdate("H:i", $remainingTime);
				$fillTimeArray 	= explode(":", $fillTime);

				if ($fillTimeArray[0] != "00") {
					$timeArray['fill_hours'] = trim($fillTimeArray[0]) . " Hours";
				}

				if ($fillTimeArray[1] != "00") {
					$timeArray['fill_minutes'] = trim($fillTimeArray[1]) . " Minutes";
				}

				$timeArray['fill_time'] = $fillTime;
				$timeArray['fill_date'] = $pre;

			} elseif ($WorkedHours < $workingHalfHours) {
				$remainingHours   = $workingHalfHours - $WorkedHours;
				$fillTime 		  = gmdate("H:i", $remainingHours);
				$fillTimeArray 	  = explode(":", $fillTime);

				if ($fillTimeArray[0] != "00") {
					$timeArray['fill_hours'] = trim($fillTimeArray[0]) . " Hours";
				}

				if ($fillTimeArray[1] != "00") {
					$timeArray['fill_minutes'] = trim($fillTimeArray[1]) . " Minutes";
				}

				$timeArray['fill_time'] = $fillTime;
				$timeArray['fill_date'] = $pre;
			}
			return $timeArray;
		}
	}



	// Save function here
	public function save()
	{
		$timeSheetId       = $this->input->post('time_sheet_id',TRUE);
		$empId             = $this->session->userdata['emp_id'];
		$taskId            = $this->input->post('task_id',TRUE);
		$projectId     	   = $this->input->post('project_id',TRUE);
		$leaveReasonId     = $this->input->post('leave_reason_id',TRUE);
		$hours         	   = $this->input->post('hours', TRUE);
		$timeSheetDate 	   = $this->input->post('time_sheet_date', TRUE);
		$note   	   	   = $this->input->post('note', TRUE);

		$this->form_validation->set_rules('project_id', $this->lang->line('project_name'), 'required');
		$this->form_validation->set_rules('task_id', $this->lang->line('task_name'), 'required');
		$this->form_validation->set_rules('hours', $this->lang->line('hours'), 'required');
		$this->form_validation->set_rules('time_sheet_date', $this->lang->line('time_sheet_date'), 'required');
		$this->form_validation->set_rules('note', $this->lang->line('note'), 'required');
		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] 	 = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {

			if((strpos($hours, ':') === false) && (strpos($hours, '.') === false )){
				$numPadded  = sprintf("%02d", $hours);
				$totalTime  =  $numPadded.":00";

			} else{
				$hours 		= str_replace('.',':',$hours);
				$arrHours 	= explode(":",$hours);
				$hour 		= sprintf("%02d", $arrHours[0]);
				$minute 	= sprintf("%02d", $arrHours[1]);
				$totalTime  =  $hour.":".$minute;
			}

			$timeSheetArray = array(
				'time_sheet_id'	    => $timeSheetId,
				'emp_id'    		=> $empId,
				'task_id'	        => $taskId,
				'project_id'	    => $projectId,
				'leave_reason_id'   => isset($leaveReasonId) ? $leaveReasonId : "",
				'hours'  			=> $totalTime,
				'time_sheet_date'   => DMYToYMD($timeSheetDate),
				'note'  			=> $note,
				'created_at'        => date('Y-m-d h:i:s'),
			);
			$timeSheetData  = $this->Mdl_time_sheet->insertUpdateRecord($timeSheetArray, 'time_sheet_id', 'tbl_time_sheet', 1);

			if(isset($timeSheetId) && $timeSheetId != ''){
				if($timeSheetData['success']){
					$dt_ci_email = new dt_ci_email();
					$empDetails = $this->Mdl_employee->getEmployeeEmail($empId);

					if($hours == '08:00'){
						$toEmail = $empDetails['email'];
						$message = 'Hello <strong>'.$empDetails['emp_name'].'</strong>,<br/><br/>
									Your Today Time Sheet Details From <strong>'.$timeSheetDate.'</strong> to <strong>'.$timeSheetDate.'</strong> has been Fill Complete.<br/><br/>
									Thank you,<br/>
									'.FROM_NAME;
						$status  = $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('time_sheet_details'), $message);
					} else if($hours != '08:00'){
						$toEmail = $empDetails['email'];
						$message = 'Hello <strong>'.$empDetails['emp_name'].'</strong>,<br/><br/>
									Please Fill Your Remaining Time Sheet From  <strong>'.$timeSheetDate.'</strong> to <strong>'.$timeSheetDate.'</strong> has been incomplete.<br/><br/>
									Thank you,<br/>
									'.FROM_NAME;
						$status  = $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('time_sheet_details_no'), $message);
					}
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('update_record'), TIMESHEET);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('update_record_error'), TIMESHEET);
				}
			}

			if (isset($timeSheetId) && $timeSheetId != '') {
				if ($timeSheetData['success']) {
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('create_record'), TIMESHEET);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('create_record_error'), TIMESHEET);
				}
			} else {
				if ($timeSheetData['success']) {
					$response['success'] = true;
					$response['msg'] 	 = sprintf($this->lang->line('update_record'), TIMESHEET);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('update_record_error'), TIMESHEET);
				}
			}
			echo json_encode($response);
		}
	}

	public function leaveManage($timeSheetId = '') // change here manage
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
			'leaveReason'  => true,
		);
		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if($timeSheetId != '') {
			$data['getLeaveReasonManageData'] = $this->Mdl_time_sheet->getLeaveReasonManageData($timeSheetId);
			if(empty($data['getLeaveReasonManageData'])){
				$timeDetails = $this->checkLastDayTotalTime();
				if(!empty($timeDetails)){
					$data['timeDetails'] = $timeDetails;
				}
			}
		} else{
			$timeDetails = $this->checkLastDayTotalTime();
			if(!empty($timeDetails)){
				$data['timeDetails'] = $timeDetails;
			}
		}

		$this->dt_ci_template->load("default", "timeSheet/v_leave_res_manage", $data);
	}


	// Save function here
	public function leaveSave()
	{
		$timeSheetId       = $this->input->post('time_sheet_id');
		$empId             = $this->session->userdata['emp_id'];
		$taskId            = $this->input->post('task_id');
		$projectId     	   = $this->input->post('project_id');
		$leaveReasonId     = $this->input->post('leave_reason_id');
		$hours         	   = $this->input->post('hours', TRUE);
		$timeSheetDate 	   = $this->input->post('time_sheet_date', TRUE);
		$note   	   	   = $this->input->post('note', TRUE);

		$this->form_validation->set_rules('leave_reason_id', $this->lang->line('leave_reason'), 'required');
		$this->form_validation->set_rules('hours', $this->lang->line('hours'), 'required');
		$this->form_validation->set_rules('time_sheet_date', $this->lang->line('time_sheet_date'), 'required');
		$this->form_validation->set_rules('note', $this->lang->line('note'), 'required');
		$this->form_validation->set_message('required', '%s is required');


		if ($this->form_validation->run() == false) {
			$response['success'] = false;
			$response['msg'] 	 = strip_tags(validation_errors(""));
			echo json_encode($response);
			exit;
		} else {
			$leaveReasonManageArray = array(
				'time_sheet_id'	    => $timeSheetId,
				'emp_id'	    	=> $empId,
				'task_id'	        => isset($taskId) ? $taskId : "",
				'project_id'	    => isset($projectId) ? $projectId : "",
				'leave_reason_id'   => isset($leaveReasonId) ? $leaveReasonId : "",
				'hours'  			=> $hours,
				'time_sheet_date'   => DMYToYMD($timeSheetDate),
				'note'  			=> $note,
			);

			$leaveReasonManageData  = $this->Mdl_time_sheet->insertUpdateRecord($leaveReasonManageArray, 'time_sheet_id', 'tbl_time_sheet', 1);

			if(isset($timeSheetId) && $timeSheetId != ''){
				if($leaveReasonManageData['success']){
					$dt_ci_email 	= new dt_ci_email();
					$empDetails 	= $this->Mdl_employee->getEmployeeEmail($empId);
					$toEmail 		= $empDetails['email'];
					$message 		= 'Hello <strong>'.$empDetails['emp_name'].'</strong>,<br/><br/>
										You are take a leave from <strong>'.$timeSheetDate.'</strong> to <strong>'.$timeSheetDate.'</strong> total 
										<strong>'.$hours.'</strong>.<br/><br/>
										Thank you,<br/>
										'.FROM_NAME;
					$status  		= $dt_ci_email->sendPasswordMail($toEmail,$this->lang->line('time_sheet_details'), $message);
				} else {
					$response['success'] = false;
					$response['msg'] 	 = sprintf($this->lang->line('update_record_error'), TIMESHEET);
				}
			}

			if (isset($timeSheetId) && $timeSheetId != '') {
				if ($leaveReasonManageData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), TIMESHEET);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), TIMESHEET);
				}
			} else {
				if ($leaveReasonManageData['success']) {
					$response['success'] = true;
					$response['msg'] = sprintf($this->lang->line('update_record'), TIMESHEET);
				} else {
					$response['success'] = false;
					$response['msg'] = sprintf($this->lang->line('update_record_error'), TIMESHEET);
				}
			}
			echo json_encode($response);
		}
	}

	public function delete()
	{
		$timeSheetId   = $this->input->post('deleteId',TRUE);
		$timeSheetData = $this->Mdl_time_sheet->deleteRecord($timeSheetId);

		if ($timeSheetData) {
			$response['success'] = true;
			$response['msg']     = sprintf($this->lang->line('delete_record'),TIMESHEET);
		} else {
			$response['success'] = false;
			$response['msg']     = sprintf($this->lang->line('error_delete_record'),TIMESHEET);
		}
		echo json_encode($response);
	}


	public function getLeaveReasonFilterDD()
	{
		$empId     	 = $this->input->post("leaveReasonFilterId");
		$searchTerm  = $this->input->post("filter_param");

		$data = array('result' => array(
			array(
				"id"        => 'Yes',
				"text"      => 'Yes'),
			array(
				"id"        => 'No',
				"text"      => 'No'),
		));

		echo json_encode($data); die;
	}
}
