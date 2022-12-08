<?php


class Mdl_employee_entry_log extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($employeeLogId)
	{
		$tables = ('tbl_employee_log');
		$this->db->where_in('emp_log_id',$employeeLogId);
		$this->db->delete($tables);
		if ($this->db->affected_rows()) {
			$response['success'] = true;
			return $response;
		} else {
			$response['success'] = false;
			return $response;
		}
	}
}
