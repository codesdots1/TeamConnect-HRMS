<?php


class Mdl_employee_attendance extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($employeeAttendanceId)
	{
		$tables = array('tbl_employee_attendance');
		$this->db->where_in('employee_attendance_id',$employeeAttendanceId);
		$this->db->delete($tables);
		$ids = is_array($employeeAttendanceId) ? implode(',',$employeeAttendanceId) : $employeeAttendanceId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}else{
			$response['success'] = "false";
		}

		return $response;
	}
	
	public function deleteEmpAttendanceRecord($empIds)
	{
		$tables = array('tbl_employee_attendance'); 
		foreach($empIds as $key => $empId){
			$empDetails = explode("_",$empId);
			$id = $empDetails[0];
			$date = $empDetails[1];
			$this->db->where('emp_id',$id);
			$this->db->where('attendance_date',DMYToYMD($date));
			$this->db->delete($tables);
			$this->db->reset_query();
			
		}
		
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}else{
			$response['success'] = "false";
		}

		return $response;
	}

	public function getEmployeeAttendanceData($employeeAttendanceId)
	{
		$this->db->select("tea.employee_attendance_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name,' | ',te.email) as emp_name,
		tea.logout_time as out_time,
		tea.login_time,
		tea.logout_time,
        date_format(tea.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date");
		$this->db->from('tbl_employee_attendance as tea');
		$this->db->join('tbl_employee as te','te.emp_id = tea.emp_id','left');
		$this->db->where('tea.employee_attendance_id', $employeeAttendanceId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}
	
	public function getEmployeeAttendanceCorrectionData($correctionId)
	{
		$this->db->select("tac.attendance_correction_id,tac.employee_attendance_id,tac.approved,tac.rejected,tac.note,te.emp_id,
		CONCAT(te.first_name,' ',te.last_name,' | ',te.email) as emp_name,
		CONCAT(te.first_name,' ',te.last_name) as name,
		CONCAT(tac.rejected,'|',tac.approved) as status,
		tac.login_time,
        tac.logout_time,
        date_format(tac.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date,
		tea.login_time as old_login_time,
        tea.logout_time as old_logout_time");
		$this->db->from('tbl_attendance_correction as tac');
		$this->db->join('tbl_employee_attendance  as tea','tea.employee_attendance_id = tac.employee_attendance_id','left');
		$this->db->join('tbl_employee as te','te.emp_id = tac.emp_id','left');
		$this->db->where('tac.attendance_correction_id', $correctionId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}
	
	public function getAttendanceDetails($empId,$attendaceDate){
		$this->db->select("tea.employee_attendance_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name, te.email,
		max(tea.logout_time) as out_time,min(tea.logout_time) as min_out_time,
		date_format(min(tea.login_time),'".PHP_TIME_MYSQL_FORMAT."') as login_time,
        date_format(max(tea.logout_time),'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
		SEC_TO_TIME(sum(TIME_TO_SEC(TIMEDIFF(tea.logout_time,tea.login_time)))) as total_time,
        date_format(tea.attendance_date,'".DATE_FORMATE_MYSQL."') as attendance_date");
		$this->db->from("tbl_employee_attendance as tea");
		$this->db->where('te.emp_id',$empId);
		$this->db->where('tea.attendance_date',DMYToYMD($attendaceDate));
		$this->db->group_by('tea.emp_id,attendance_date');
		$this->db->join('tbl_employee as te','te.emp_id = tea.emp_id','left');
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}
	
    public function getEmployeeName($empId=''){
		if($empId != ""){
			$this->db->select("CONCAT(te.first_name,' ',te.last_name,' | ',te.email) as emp_name");
			$this->db->from("tbl_employee as te");
			$this->db->where('te.emp_id',$empId);
			$query = $this->db->get();
			$queryData = $query->row_array();
			return $queryData;
		}
	}
	
	public function attendanceLogout($empId=''){
		if($empId  != ""){
			$dataAttendance = array(
				'logout_time'  	=> date('H:i:s')
			);
			$dataWhere = array(
				'emp_id' 			=> $empId,
				'attendance_date'  	=> date('Y-m-d'),
				'logout_time' 		=> '00:00:00'
			);
					
			$this->db->update('tbl_employee_attendance',$dataAttendance,$dataWhere);
			if ($this->db->affected_rows()) {
				$response['success'] = "true";
			}else{
				$response['success'] = "false";
			}
		}else{
			$response['success'] = "false";
		}

		return $response;
			
	}

	public function getCorrectionStatus($attendanceId)
	{
		if($attendanceId != 0){
			$this->db->select("attendance_correction_id,rejected,approved");
			$this->db->from("tbl_attendance_correction");
			$this->db->where('employee_attendance_id',$attendanceId);
			$query = $this->db->get();
			if($query->num_rows()  > 0){
				$queryData = $query->row_array();
				if($queryData['rejected'] == 'false' && $queryData['approved'] == 'false')
					return array($queryData['attendance_correction_id'],'requested');
					else if($queryData['rejected'] == 'true' && $queryData['approved'] == 'false')
						return array($queryData['attendance_correction_id'],'rejected');
					else if($queryData['approved'] == 'true' && $queryData['rejected'] == 'false')
						return array($queryData['attendance_correction_id'],'approved');
			} else{
					return array('0', 'applyRequest');
			}
		}
	}
	
	public function getOldAttendanceDetails($attendanceId = 0){
		if($attendanceId != 0){
			$this->db->select("*");
			$this->db->from("tbl_employee_attendance");
			$this->db->where('employee_attendance_id',$attendanceId);
			$data = $this->db->get()->row_array();
			return $data;
		}	
	}


	public function saveCsv($data, $userId){

		created_info_merge($data, $userId);

		//printArray($data,1);

		$this->db->insert("tbl_employee_attendance", $data);

	}

	public function getTotalEmployee(){
		$this->db->select("count(*) as empName");
		$this->db->from('tbl_employee_attendance');
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

}
