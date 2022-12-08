<?php


class Mdl_designation extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($designationId)
	{
		$tables = array('tbl_designation');
		$this->db->where_in('designation_id',$designationId);
		$this->db->delete($tables);
		$ids = is_array($designationId) ? implode(',',$designationId) : $designationId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getDesignationData($designationId)
	{
		$this->db->select("td.designation_id,td.designation_name,td.description");
		$this->db->from('tbl_designation as td');
		$this->db->where('td.designation_id', $designationId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	function getDesignationDD($data)
	{
		$this->db->select('td.designation_id as id,td.designation_name as text');
		$this->db->from('tbl_designation as td');
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("td.designation_name", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}
}
