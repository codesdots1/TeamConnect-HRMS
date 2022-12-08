<?php


class Mdl_time_sheet extends DT_CI_Model
{

	public function __construct(){
		parent::__construct();
	}

	public function deleteRecord($timeSheetId){
		$tables = array('tbl_time_sheet');
		$this->db->where_in('time_sheet_id',$timeSheetId);
		$this->db->delete($tables);
		$ids = is_array($timeSheetId) ? implode(',',$timeSheetId) : $timeSheetId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getTimeSheetData($timeSheetId){
		$this->db->select("tts.time_sheet_id,tt.task_id,tt.task_name,tp.project_id,tp.project_name,te.emp_id,
		CONCAT(te.first_name,' ',te.last_name) as emp_name,tlr.leave_reason_id,tlr.leave_reason_name,
		tts.hours,tts.note,date_format(tts.time_sheet_date,'" . DATE_FORMATE_MYSQL . "') as time_sheet_date");
		$this->db->from('tbl_time_sheet as tts');
		$this->db->join('tbl_project as tp','tp.project_id = tts.project_id','left');
		$this->db->join('tbl_employee as te','te.emp_id = tts.emp_id','left');
		$this->db->join("tbl_task as tt","tts.task_id = tt.task_id","left");
		$this->db->join("tbl_leave_reason as tlr","tlr.leave_reason_id = tts.leave_reason_id","left");
		$this->db->where('tts.time_sheet_id', $timeSheetId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getTaskData($timeSheetId){
		$this->db->select("tts.time_sheet_id,tt.task_id,tt.task_name,tp.project_id,tp.project_name,te.emp_id,
		CONCAT(te.first_name,' ',te.last_name) as emp_name,tlr.leave_reason_id,tlr.leave_reason_name,
		SUM(tts.hours) as hours,tts.note,date_format(tts.time_sheet_date,'" . DATE_FORMATE_MYSQL . "') as time_sheet_date");
		$this->db->from('tbl_time_sheet as tts');
		$this->db->join('tbl_project as tp','tp.project_id = tts.project_id','left');
		$this->db->join('tbl_employee as te','te.emp_id = tts.emp_id','left');
		$this->db->join("tbl_task as tt","tts.task_id = tt.task_id","left");
		$this->db->join("tbl_leave_reason as tlr","tlr.leave_reason_id = tts.leave_reason_id","left");
		$this->db->where('tts.time_sheet_id', $timeSheetId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getTimeSheetDetails($empId,$timeSheetDate){
		$this->db->select("tts.time_sheet_id,te.emp_id,CONCAT(te.first_name,' ',te.last_name) as emp_name, 
		tp.project_id,tp.project_name,ts.task_id,ts.task_name,
		TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(tts.hours))),'%Hh %im') as hours,
		TIME_FORMAT(SEC_TO_TIME(sum(TIME_TO_SEC(tts.hours))),'%H:%i') as total_time,
        date_format(tts.time_sheet_date,'".DATE_FORMATE_MYSQL."') as time_sheet_date,
        date_format(tts.created_at,'".DATE_FORMATE_MYSQL."') as created_at");
		$this->db->from('tbl_time_sheet as tts');
		$this->db->where('te.emp_id',$empId);
		$this->db->where('tts.time_sheet_date',DMYToYMD($timeSheetDate));
		$this->db->group_by('tts.emp_id,time_sheet_date');
		$this->db->join('tbl_employee as te','te.emp_id = tts.emp_id','left');
		$this->db->join('tbl_project as tp','tp.project_id = tts.project_id','left');
		$this->db->join('tbl_task as ts','ts.task_id = tts.task_id','left');
		$query = $this->db->get();
		$queryData = $query->row_array();
		//printArray($this->db->last_query(),1);
		return $queryData;
	}

	public function getLeaveReasonManageData($timeSheetId)
	{
		$this->db->select("tts.time_sheet_id,tlr.leave_reason_id,tlr.leave_reason_name,
		date_format(tts.time_sheet_date,'" . DATE_FORMATE_MYSQL . "') as time_sheet_date,,tts.hours,tts.note");
		$this->db->from('tbl_time_sheet as tts');
		$this->db->join("tbl_leave_reason as tlr","tts.leave_reason_id = tlr.leave_reason_id","left");
		$this->db->where('tts.time_sheet_id', $timeSheetId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getSaturdayInfo()
	{
		$this->db->select("tmwd.work_week_state");
		$this->db->from('tbl_month_work_day as tmwd');
		$query = $this->db->get();
 		$queryData = $query->result_array();
		return $queryData;
	}

	public function getTotalEmployee()
	{
		$this->db->select("count(*) as empName");
		$this->db->from('tbl_time_sheet');
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

}
