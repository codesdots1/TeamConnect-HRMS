<?php


class Mdl_employee_shift extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($shiftId)
	{
//		$shiftId = explode(',',$shiftId);
		$tables = array('tbl_employee_shift');
		$this->db->where_in('shift_id',$shiftId);
		$this->db->delete($tables);
		$ids = is_array($shiftId) ? implode(',',$shiftId) : $shiftId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getEmployeeShiftData($shiftId)
	{
		$this->db->select("tes.shift_id,tes.shift_name,tes.start_time,
        tes.end_time,tes.total_hours,tes.is_active");
		$this->db->from('tbl_employee_shift as tes');
		$this->db->where('tes.shift_id', $shiftId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	function getEmployeeShiftDD($data)
	{
		$this->db->select('tes.shift_id as id,tes.shift_name as text');
		$this->db->from('tbl_employee_shift as tes');
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("tes.shift_name", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}
}
