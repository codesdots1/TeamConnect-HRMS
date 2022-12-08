<?php


class Mdl_employee_type extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($typeId)
	{
		$tables = array('tbl_employee_type');
		$this->db->where_in('type_id', $typeId);
		$this->db->delete($tables);
		$ids = is_array($typeId) ? implode(',', $typeId) : $typeId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getEmployeeTypeData($typeId)
	{
		$this->db->select("et.type_id,et.type_name,et.description");
		$this->db->from('tbl_employee_type as et');
		$this->db->where('et.type_id', $typeId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getEmployeeLeaveToday()
	{
		$this->db->select("COUNT(*) as empLeave");
		$this->db->from('tbl_employee_leaves');
		$this->db->where('DATE(apply_date) = CURDATE()');
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}
	public function getEmployeeTotalLeave()
	{
		$this->db->select("COUNT(*) as employeeTotalLeave");
		$this->db->from('tbl_employee_leaves');
		$this->db->where('emp_id',$this->session->userdata['emp_id']);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getEmployeeTotalTaken()
	{
		$this->db->select("SUM(tel.no_of_days)");
		$this->db->from('tbl_employee_leaves as tel');
		$this->db->where('tel.emp_id',$this->session->userdata['emp_id']);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getEmployeeTotalTime()
	{
		$this->db->select("date_format(tea.login_time,'".PHP_TIME_MYSQL_FORMAT."') as login_time,
        date_format(tea.logout_time,'".PHP_TIME_MYSQL_FORMAT."') as logout_time,
		TIMEDIFF(tea.logout_time,tea.login_time) as total_time");
		$this->db->from('tbl_employee_attendance as tea');
		$this->db->where('DATE(tea.attendance_date) = CURDATE()');
		$this->db->where('emp_id',$this->session->userdata['emp_id']);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getEmployeeLeaveType()
	{
		$this->db->select("CONCAT(e.first_name,' ',e.last_name) as employee,tlt.leave_type");
		$this->db->from('tbl_employee as e');
		$this->db->join('tbl_employee_leaves as tel', 'tel.emp_id = e.emp_id', 'left');
		$this->db->join('tbl_leave_type as tlt', 'tel.leave_type_id = tlt.leave_type_id', 'left');
		$this->db->where('e.emp_id', $this->session->userdata['emp_id']);
		$this->db->where_in('tlt.leave_type', 'paid sick leave');
		$this->db->or_where('DATE(tel.leave_from_date)=CURDATE()');
		$query = $this->db->get();
		$queryData = $query->result_array();
		return $queryData;
	}

	function getTypeDD($data)
	{
		$this->db->select('et.type_id as id,et.type_name as text');
		$this->db->from('tbl_employee_type as et');
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("et.type_name", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}

}
