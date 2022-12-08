<?php


class Mdl_shift_management extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($shiftId)
	{
		$tables = array('tbl_employee,tbl_employee_shift');
		$this->db->where_in('emp_id',$shiftId);
		$this->db->delete($tables);
		$ids = is_array($shiftId) ? implode(',',$shiftId) : $shiftId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getShiftManagementData($employeeId)
	{
		$this->db->select("te.emp_id,concat(te.emp_id,' | ',te.first_name,' ',te.last_name) as emp_name,tes.shift_id,tes.shift_name");
		$this->db->from('tbl_employee as te');
		$this->db->join('tbl_employee_shift as tes','tes.shift_id = te.shift_id','left');
		$this->db->where('te.emp_id', $employeeId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}
}
