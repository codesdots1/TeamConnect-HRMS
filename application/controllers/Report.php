<?php


class Report extends DT_CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Curl');
		$this->load->library("Dt_ci_excel");
		$this->load->model(array("Mdl_employee_leave_type","Mdl_employee_attendance","Mdl_report","Mdl_time_sheet","Mdl_team_management"));
		$this->lang->load(array('employee_leave_type','employee_attendance','report'));
	}

	public function EmployeeLeaveReport($empId='')
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

		if($empId != ""){
			$data['empId'] = $empId;
		}
		$select2 = array(
			'leaveType'  	=> true,
			'leaveStatus'   => true
		);
		if(strtolower($this->session->userdata('role')) != 'employee')
			$select2['employeeName'] = 'true';


		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if(strtolower($this->session->userdata('role')) != 'admin'){
			$loginEmp 	= $this->session->userdata('emp_id');
			$teamData 	= $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds 	= explode(",",$teamData['emp_id_listing']);
			array_push($empIds,$loginEmp);
			$data['empIds'] = $empIds;
		}

		$this->dt_ci_template->load("default", "report/v_employee_leave_report", $data);
	}

	// ajax call to the data listing
	public function getEmployeeLeaveListing()
	{
		$empId 			= $this->input->post('empId',TRUE);
		$startDate 		= $this->input->post('startDate',TRUE);
		$endDate 		= $this->input->post('endDate',TRUE);
		$leaveType 		= $this->input->post('leaveType',TRUE);
		$leaveStatus 	= $this->input->post('leaveStatus',TRUE);
		$role 			= $this->session->userdata('role');

		if(strtolower($this->session->userdata('role')) != 'admin' && ($empId == '' || $empId == 'all')){
			$loginEmp 	= $this->session->userdata('emp_id');
			$teamData 	= $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds 	= explode(",",$teamData['emp_id_listing']);
			array_push($empIds,$loginEmp);
		}

		$this->load->library('datatables');
		$this->datatables->select("tel.leave_id,concat(te.first_name,' ',te.last_name) as name,tlt.leave_type,
		date_format(tel.apply_date,'".DATE_FORMATE_MYSQL."') as apply_date,
		date_format(tel.leave_from_date,'".DATE_FORMATE_MYSQL."') as leave_from_date,
		date_format(tel.leave_to_date,'".DATE_FORMATE_MYSQL."') as leave_to_date,
		tel.leave_reason,tel.no_of_days,tel.is_active,tel.is_rejected");
		$this->datatables->from("tbl_employee_leaves as tel");

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
		}
		if($empId  != "" && $empId != 'all'){
			$this->datatables->where('tel.emp_id', $empId );
		}
		if($leaveType  != ""){
			$this->datatables->where('tlt.leave_type_id', $leaveType );
		}
		if($leaveStatus  != ""){
			if(strtolower($leaveStatus) == "pending"){
				$where = array('tel.is_rejected' => 0 , 'tel.is_active' => 0);
				$this->datatables->where($where);
			}else if(strtolower($leaveStatus) == "approved"){
				$where = array('tel.is_rejected' => 0 , 'tel.is_active' => 1);
				$this->datatables->where($where);
			}else if(strtolower($leaveStatus) == "rejected"){
				$where = array('tel.is_rejected' => 1 , 'tel.is_active' => 0);
				$this->datatables->where($where);
			}
		}
		$this->datatables->join("tbl_leave_type as tlt","tlt.leave_type_id = tel.leave_type_id");
		$this->datatables->join("tbl_employee as te","te.emp_id = tel.emp_id");
		echo $this->datatables->generate();

	}


	//insert and update function
	public function EmployeeLeaveDetails($leaveTypeId = '') // change here manage
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


		if($leaveTypeId != '') {
			$data['getEmployeeLeaveData'] = $this->Mdl_employee_leave_type->getEmployeeLeaveData($leaveTypeId);
			$empId 						  = $data['getEmployeeLeaveData']['emp_id'];
			$leaveId 					  = $data['getEmployeeLeaveData']['leave_type_id'];
			$data['leaveTypeData'] 		  = $this->Mdl_employee_leave_type->getTotalDays($leaveId,$empId);
		}
		$this->dt_ci_template->load("default", "report/v_employee_leave_data_report", $data);
	}



	function exportToExcelLeave()
	{
		$empId 		= $this->input->post('emp_name',TRUE);
		$startDate  = $this->input->post('start_date',TRUE);
		$endDate 	= $this->input->post('end_date',TRUE);
		$leaveType  = $this->input->post('leave_type',TRUE);
		$status 	= $this->input->post('leave_status',TRUE);
		$object 	= new PHPExcel();
		$object->setActiveSheetIndex(0);

		$table_columns = array("Sr No.","Name", "Leave Type", "Leave From Date", "Leave To Date", "Apply Date", "Reason","Status");
		$column 	   = 0;

		$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$object->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('G')->setWidth(50);
		$object->getActiveSheet()->getColumnDimension('H')->setWidth(25);
		$from = "A1"; // or any value
		$to   = "H1"; // or any value
		$object->getActiveSheet()->getStyle( "$from:$to" )->getFont()->setBold( true );
		foreach($table_columns as $field)
		{
			$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
			$column++;
		}
		$param = array(
			'empId' 	  => $empId,
			'startDate'   => $startDate,
			'endDate' 	  =>  $endDate,
			'leaveType'   => $leaveType,
			'leaveStatus' => $status
		);

		$employeeLeaveData = $this->Mdl_report->exportToExcelLeave($param);
		$excel_row 		   = 2;
		$increament 	   = 1;
		foreach($employeeLeaveData as $row)
		{
			$status = "";
			if($row['is_active'] == 1 && $row['is_rejected'] == 0){
				$status = "Approved";
			}else if ($row['is_active'] == 0 && $row['is_rejected'] == 1){
				$status = "Rejected";
			}else{
				$status = "Pending";
			}
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,$increament);
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['name']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['leave_type']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['leave_from_date']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['leave_to_date']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['apply_date']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['leave_reason']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $status);
			$increament++;
			$excel_row++;
		}

		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="EmployeeLeaveReport.xls"');
		$object_writer->save('php://output');
	}


	public function getLeaveStatusDD()
	{
		$leaveStatusId     	= $this->input->post("leaveStatusid");
		$searchTerm  		= $this->input->post("filter_param");

		$data = array('result' => array(
			array(
				"id"        => 'Pending',
				"text"      => 'Pending'
			),
			array(
				"id"        => 'Approved',
				"text"      => 'Approved'
			),
			array(
				"id"        => 'Rejected',
				"text"      => 'Rejected'
			)
		));
		echo json_encode($data);
	}



	/*********************************************************Attendance******************************************************************************/
	public function EmployeeAttendanceReport($empId='')
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

		$select2 = array();
		if(strtolower($this->session->userdata('role')) != 'employee')
			$select2['employeeName'] = 'true';


		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if(strtolower($this->session->userdata('role')) != 'admin'){
			$loginEmp 	= $this->session->userdata('emp_id');
			$teamData 	= $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds 	= explode(",",$teamData['emp_id_listing']);
			array_push($empIds,$loginEmp);
			$data['empIds'] = $empIds;
		}

		if($empId != ""){
			$data['empId'] = $empId;
		}

		$this->dt_ci_template->load("default", "report/v_employee_attendance_report", $data);
	}



	function exportToExcelAttendance()
	{
		$empId 		= $this->input->post('emp_id',TRUE);
		$startDate 	= $this->input->post('start_date',TRUE);
		$endDate 	= $this->input->post('end_date',TRUE);

		$object = new PHPExcel();
		$object->setActiveSheetIndex(0);

		$table_columns  = array("Sr No.","Name", "Entry Time", "Exit Time", "Total Working Hour", "Date");
		$column 		= 0;

		$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$object->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$from 	= "A1"; // or any value
		$to 	= "F1"; // or any value
		$object->getActiveSheet()->getStyle( "$from:$to" )->getFont()->setBold( true );
		foreach($table_columns as $field)
		{
			$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
			$column++;
		}
		$param = array(
			'empId' => $empId,
			'startDate' => $startDate,
			'endDate' =>  $endDate
		);

		$employeeLeaveData  = $this->Mdl_report->exportToExcelAttendance($param);
		$excel_row 			= 2;
		$increament 	 	= 1;
		foreach($employeeLeaveData as $row)
		{
			$logoutTime  = "";
			$currentDate = date('d-m-Y');

			if(($row['out_time'] == "00:00:00" || $row['min_out_time'] == "00:00:00" ) && $row['attendance_date'] == $currentDate ){
				$logoutTime = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; In';
			} else if(($row['out_time'] == "00:00:00" || $row['min_out_time'] == "00:00:00" ) && $row['attendance_date'] != $currentDate ){
				$logoutTime = '00:00:00';
			} else{
				$logoutTime = $row['logout_time'];
			}

			$totalTime = "";

			if(strpos($row['total_time'], '-') !==  false){
				$totalTime = '00:00:00';
			} else{
				$totalTime = $row['total_time'];
			}
			$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row, $increament);
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['emp_name']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['login_time']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $logoutTime);
			$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $totalTime);
			$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['attendance_date']);
			$increament++;
			$excel_row++;
		}

		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="EmployeeAttendanceReport.xls"');
		$object_writer->save('php://output');
	}


	public function EmployeeAttendanceDetails()
	{
		$empId 		= $this->input->post('empId',TRUE);
		$startDate  = $this->input->post('startDate',TRUE);
		$endDate 	= $this->input->post('endDate',TRUE);

		if(strtolower($this->session->userdata('role')) != 'admin' && ($empId == '' || $empId == 'all')){
			$loginEmp =  $this->session->userdata('emp_id');
			$data = $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds = explode(",",$data['emp_id_listing']);
			array_push($empIds,$loginEmp);
		}

		$this->load->library('datatables');
		$this->datatables->select("tea.employee_attendance_id,te.emp_id,(CONCAT(te.first_name,' ',te.last_name)) as emp_name, 
		 te.email,tr.role,max(tea.logout_time) as out_time,min(tea.logout_time) as min_out_time,
		 date_format(min(tea.login_time),'".PHP_TIME_MYSQL_FORMAT."') as login_time,
         date_format(max(tea.logout_time),'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
         SEC_TO_TIME(sum(TIME_TO_SEC(TIMEDIFF(tea.logout_time,tea.login_time)))) as total_time,
         date_format(tea.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date");

		if($startDate != ''){
			$this->datatables->where('tea.attendance_date >=', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->datatables->where('tea.attendance_date <=', DMYToYMD($endDate));
		}
		if(!empty($empIds)){
			$this->datatables->where_in('te.emp_id', $empIds );
		}
		if($empId  != "" && $empId  != "all"){
			$this->datatables->where('te.emp_id', $empId );
		}

		$this->datatables->from("tbl_employee_attendance as tea");
		$this->datatables->where('LOWER(tr.role) !=', 'admin' );
		$this->datatables->group_by('tea.emp_id,attendance_date');
		$this->datatables->join('tbl_employee as te','te.emp_id = tea.emp_id','left');
		$this->datatables->join('tbl_role as tr','te.role_id = tr.role_id','left');
		echo $this->datatables->generate();
	}


	/*********************************************************Task******************************************************************************/

	public function EmployeeTaskReport($empId = '')
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

		if($empId != ""){
			$data['empId'] = $empId;
		}

		$select2 = array(
			'leaveReasonFilter'  => true,
			'project' 	   		 => true,
			'task' 		   		 => true,
		);

		if(strtolower($this->session->userdata('role')) != 'employee')
			$select2['employeeName'] = 'true';

		$data['select2'] = $this->load->view("commonMaster/v_select2",$select2,true);

		if(strtolower($this->session->userdata('role')) != 'admin'){
			$loginEmp 	= $this->session->userdata('emp_id');
			$teamData 	= $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds 	= explode(",",$teamData['emp_id_listing']);
			array_push($empIds,$loginEmp);
			$data['empIds'] = $empIds;
		}

		$this->dt_ci_template->load("default", "report/v_employee_task_report", $data);
	}

	public function getEmployeeTaskListing()
	{
		$empId 			= $this->input->post('empId',TRUE);
		$startDate 		= $this->input->post('startDate',TRUE);
		$endDate 		= $this->input->post('endDate',TRUE);
		$leaveReason 	= $this->input->post('leaveReason',TRUE);
		$project 		= $this->input->post('project',TRUE);
		$task 			= $this->input->post('task',TRUE);
		$role 			= $this->session->userdata('role');

		if(strtolower($this->session->userdata('role')) != 'admin' && ($empId == '' || $empId == 'all')){
			$loginEmp 	=  $this->session->userdata('emp_id');
			$data 		= $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds 	= explode(",",$data['emp_id_listing']);
			array_push($empIds,$loginEmp);
		}


		$this->load->library('datatables');
		$this->datatables->select("tts.time_sheet_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name,
		tp.project_id,tp.project_name,ts.task_id,ts.task_name,tr.role,tts.hours,tts.note,tl.leave_reason_id,tl.leave_reason_name,
        date_format(tts.time_sheet_date,'".DATE_FORMATE_MYSQL."') as time_sheet_date");
		$this->datatables->from("tbl_time_sheet as tts");

		if($startDate != ''){
			$this->datatables->where('tts.time_sheet_date >= ', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->datatables->where('tts.time_sheet_date <=', DMYToYMD($endDate));
		}
		if(!empty($empIds)){
			$this->datatables->where_in('te.emp_id', $empIds );
		}
		if($empId  != "" && $empId  != 'all'){
			$this->datatables->where('te.emp_id', $empId );
		}
		if ($leaveReason == 'Yes'){
			$this->datatables->where('tl.leave_reason_id !=',0);
		}else if($leaveReason == 'No'){
			//$this->db->where('tp.project_id !=' , 0);

			if($project  != "" && $project  != "all"){
				$this->db->where('tp.project_id', $project );
			}
			if($task  != "" && $task  != "all"){
				$this->db->where('ts.task_id', $task );
			}
		}else if($leaveReason == ''){
			if($project  != "" && $project  != "all"){
				$this->db->where('tp.project_id', $project );
			}else if($project  == "all"){
				$this->db->where('tp.project_id !=' , 0);
			}
			if($task  != "" && $task  != "all"){
				$this->db->where('ts.task_id', $task );
			}else if($task  == "all"){
				$this->db->where('ts.task_id !=' , 0);
			}
		}

		$this->datatables->join('tbl_employee as te','tts.emp_id = te.emp_id');
		$this->datatables->join('tbl_project as tp','tp.project_id = tts.project_id','left');
		$this->datatables->join('tbl_leave_reason as tl','tl.leave_reason_id = tts.leave_reason_id','left');
		$this->datatables->join('tbl_task as ts','ts.task_id = tts.task_id','left');
		$this->datatables->join('tbl_role as tr','te.role_id = tr.role_id','left');

		echo $this->datatables->generate();
	}

	public function EmployeeTaskDetails($timeSheetId = '') // change here manage
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


		if($timeSheetId != '') {
			$data['getTimeSheetData'] = $this->Mdl_time_sheet->getTaskData($timeSheetId);
		}
		$this->dt_ci_template->load("default", "report/v_employee_task_data_report", $data);
	}

	function exportToExcelTask()
	{
		$empId 		= $this->input->post('emp_name',TRUE);
		$startDate 	= $this->input->post('start_date',TRUE);
		$endDate 		= $this->input->post('end_date',TRUE);
		$leaveReason 	= $this->input->post('leave_reason',TRUE);
		$project 		= $this->input->post('project_id',TRUE);
		$task 			= $this->input->post('task',TRUE);
		$role 			= $this->session->userdata('role');

		$object = new PHPExcel();
		$object->setActiveSheetIndex(0);

		$table_columns = array("Sr No.","Name", "Project", "Task", "Leave Reason","Note", "Time Taken","Date");
		$column 		= 0;

		$object->getActiveSheet()->getColumnDimension('A')->setWidth(5);
		$object->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('D')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('E')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('F')->setWidth(25);
		$object->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$object->getActiveSheet()->getColumnDimension('H')->setWidth(25);
		$from  = "A1"; // or any value
		$to 	= "H1"; // or any value
		$object->getActiveSheet()->getStyle( "$from:$to" )->getFont()->setBold( true );
		foreach($table_columns as $field)
		{
			$object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
			$column++;
		}
		$param = array(
			'empId' 		=> $empId,
			'startDate' 	=> $startDate,
			'endDate' 		=> $endDate,
			'leaveReason' 	=> $leaveReason,
			'project' 		=> $project,
			'task' 		=> $task,
			'role' 		=> $role
		);

		$employeeLeaveData = $this->Mdl_report->exportToExcelTask($param);
		$excel_row = 2;
		$increament = 1;
		foreach($employeeLeaveData as $row)
		{

			$object->getActiveSheet()->setCellValueByColumnAndRow(0, $excel_row,$increament);
			$object->getActiveSheet()->setCellValueByColumnAndRow(1, $excel_row, $row['emp_name']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(2, $excel_row, $row['project_name']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(3, $excel_row, $row['task_name']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(4, $excel_row, $row['leave_reason_name']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(5, $excel_row, $row['note']);
			$object->getActiveSheet()->setCellValueByColumnAndRow(6, $excel_row, $row['hours']." Hours");
			$object->getActiveSheet()->setCellValueByColumnAndRow(7, $excel_row, $row['time_sheet_date']);
			$increament++;
			$excel_row++;
		}

		$object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="EmployeeTaskReport.xls"');
		$object_writer->save('php://output');
	}
}
