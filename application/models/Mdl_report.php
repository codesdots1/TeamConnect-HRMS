<?php
class Mdl_report extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model('Mdl_leave_type');
	}


	public function exportToExcelLeave($param)
	{	
		$empId = $param['empId'];
		$startDate = $param['startDate'];
		$endDate = $param['endDate'];
		$leaveType = $param['leaveType'];
		$leaveStatus = $param['leaveStatus'];
		
		if(strtolower($this->session->userdata('role')) != 'admin' && ($empId == '' || $empId == 'all')){
			$loginEmp = $this->session->userdata('emp_id');
			$teamData = $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds = explode(",",$teamData['emp_id_listing']);
			array_push($empIds,$loginEmp);
		}
		
		$this->db->select("tel.leave_id,concat(te.first_name,' ',te.last_name) as name,tlt.leave_type,
		date_format(tel.apply_date,'".DATE_FORMATE_MYSQL."') as apply_date,
		date_format(tel.leave_from_date,'".DATE_FORMATE_MYSQL."') as leave_from_date,
		date_format(tel.leave_to_date,'".DATE_FORMATE_MYSQL."') as leave_to_date,
		tel.leave_reason,tel.no_of_days,tel.is_active,tel.is_rejected");
		$this->db->from("tbl_employee_leaves as tel");
		/*if($role == 'admin' ){
			$this->datatables->where('is_active != ',1);
			$this->datatables->where('is_rejected != ',1);
		}*/
		if($startDate != ''){
			$this->db->where('tel.leave_from_date >=', DMYToYMD($startDate));
			$this->db->where('tel.leave_to_date >=', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->db->where('tel.leave_from_date <=', DMYToYMD($endDate));
		    $this->db->where('tel.leave_to_date <=', DMYToYMD($endDate));
		}
		if(!empty($empIds)){
			$this->db->where_in('tel.emp_id', $empIds );
		}
		if($empId  != "" && $empId  != "all"){
			$this->db->where('tel.emp_id', $empId );
		}
		if($leaveType  != ""){
			$this->db->where('tlt.leave_type_id', $leaveType );
		}
		if($leaveStatus  != ""){
			if(strtolower($leaveStatus) == "pending"){
				$where = array('tel.is_rejected' => 0 , 'tel.is_active' => 0);
				$this->db->where($where);
			}else if(strtolower($leaveStatus) == "approved"){
				$where = array('tel.is_rejected' => 0 , 'tel.is_active' => 1);
				$this->db->where($where);
			}else if(strtolower($leaveStatus) == "rejected"){
				$where = array('tel.is_rejected' => 1 , 'tel.is_active' => 0);
				$this->db->where($where);
			}
		}
		$this->db->join("tbl_leave_type as tlt","tlt.leave_type_id = tel.leave_type_id");
		$this->db->join("tbl_employee as te","te.emp_id = tel.emp_id");
		$this->db->order_by('tel.apply_date','DESC');
		$query = $this->db->get();
		$queryData = $query->result_array();
		return $queryData;


	}
	
	/*****************************************************************Attendance****************************************************************/
	
	public function exportToExcelAttendance($param)
	{	
		$empId = $param['empId'];
		$startDate = $param['startDate'];
		$endDate = $param['endDate'];
		
		if(strtolower($this->session->userdata('role')) != 'admin' && ($empId == '' || $empId == 'all')){
			$loginEmp = $this->session->userdata('emp_id');
			$teamData = $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds = explode(",",$teamData['emp_id_listing']);
			array_push($empIds,$loginEmp);
		}
		
		$this->db->select("tea.employee_attendance_id,te.emp_id,(CONCAT(te.first_name,' ',te.last_name)) as emp_name, 
		te.email,tr.role,max(tea.logout_time) as out_time,min(tea.logout_time) as min_out_time,
		date_format(min(tea.login_time),'".PHP_TIME_MYSQL_FORMAT."') as login_time,
        date_format(max(tea.logout_time),'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
        SEC_TO_TIME(sum(TIME_TO_SEC(TIMEDIFF(tea.logout_time,tea.login_time)))) as total_time,
        date_format(tea.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date");
		if($startDate != ''){
			$this->db->where('tea.attendance_date >=', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->db->where('tea.attendance_date <=', DMYToYMD($endDate));
		}
		if(!empty($empIds)){
			$this->db->where_in('te.emp_id', $empIds );
		}
		if($empId  != "" && $empId  != "all"){
			$this->db->where('te.emp_id', $empId );
		}
		$this->db->from("tbl_employee_attendance as tea");
		$this->db->where('LOWER(tr.role) !=', 'admin' );
		$this->db->group_by('tea.emp_id,attendance_date');
		$this->db->join('tbl_employee as te','te.emp_id = tea.emp_id','left');
		$this->db->join('tbl_role as tr','te.role_id = tr.role_id','left');
		$this->db->order_by('tea.attendance_date','DESC');
		$query = $this->db->get();
		$queryData = $query->result_array();
		return $queryData;


	}
	
	/*****************************************************************Task****************************************************************/
	
	public function exportToExcelTask($param){
	
		$empId = $param['empId'];
		$startDate = $param['startDate'];
		$endDate = $param['endDate'];
		$leaveReason = $param['leaveReason'];
		$project = $param['project'];
		$task = $param['task'];
		$role = $param['role'];
	

		if(strtolower($this->session->userdata('role')) != 'admin' && ($empId == '' || $empId == 'all')){
			$loginEmp = $this->session->userdata('emp_id');
			$teamData = $this->Mdl_team_management->getTeamMember($loginEmp);
			$empIds = explode(",",$teamData['emp_id_listing']);
			array_push($empIds,$loginEmp);
		}

		$this->db->select("tts.time_sheet_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name,
		tp.project_id,tp.project_name,ts.task_id,ts.task_name,tr.role,tts.hours,tts.note,tl.leave_reason_id,tl.leave_reason_name,
        date_format(tts.time_sheet_date,'".DATE_FORMATE_MYSQL."') as time_sheet_date");
		$this->db->from("tbl_time_sheet as tts");
		
		if($startDate != ''){
			$this->db->where('tts.time_sheet_date >= ', DMYToYMD($startDate));
		}
		if($endDate != ''){
			$this->db->where('tts.time_sheet_date <=', DMYToYMD($endDate));
		}
		if(!empty($empIds)){
			$this->db->where_in('te.emp_id', $empIds );
		}
		if($empId  != "" && $empId  != 'all'){
			$this->db->where('te.emp_id', $empId );
		}
		if ($leaveReason == 'Yes'){
			$this->db->where('tl.leave_reason_id !=',0);
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
		
		$this->db->join('tbl_employee as te','tts.emp_id = te.emp_id');
		$this->db->join('tbl_project as tp','tp.project_id = tts.project_id','left');
		$this->db->join('tbl_leave_reason as tl','tl.leave_reason_id = tts.leave_reason_id','left');
		$this->db->join('tbl_task as ts','ts.task_id = tts.task_id','left');
		$this->db->join('tbl_role as tr','te.role_id = tr.role_id','left');
		$this->db->order_by('tts.time_sheet_date','DESC');
		$query = $this->db->get();
		$queryData = $query->result_array();
		return $queryData;
	}

	
}
