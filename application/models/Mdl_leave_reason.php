<?php


class Mdl_leave_reason extends DT_CI_Model
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($leaveReasonId)
	{
		$tables = array('tbl_leave_reason');
		$this->db->where_in('leave_reason_id',$leaveReasonId);
		$this->db->delete($tables);
		$ids = is_array($leaveReasonId) ? implode(',',$leaveReasonId) : $leaveReasonId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getData($leaveReasonId = '')
	{
		if($leaveReasonId != '') {
			$this->db->where('leave_reason_id', $leaveReasonId);
		}
		$data = $this->db->get("tbl_leave_reason");
		$query = $data->result_array();
		return $query;
	}

	function getLeaveReasonDD($data)
	{
		$this->db->select('tlr.leave_reason_id as id,tlr.leave_reason_name as text');
		$this->db->from('tbl_leave_reason as tlr');
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("tlr.leave_reason_name", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}

}
