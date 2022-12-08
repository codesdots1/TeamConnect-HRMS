<?php


class Mdl_department extends DT_CI_Model
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}


//	public function deleteRecord($departmentId = '')
//	{
//		$tables = array('tbl_department');
//		$this->db->where_in('department_id', $departmentId);
//		$this->db->delete($tables);
//		$ids = is_array($departmentId) ? implode(',', $departmentId) : $departmentId;
//		if ($this->db->affected_rows()) {
//			$response['success'] = true;
//			logActivity('Department Deleted [GenderID: ' . $ids . ']', $this->data['userId'], 'Department');
//
//			return $response;
//		} else {
//			$response['success'] = false;
//			return $response;
//		}
//
//	}

	public function deleteRecord($departmentId)
	{
		$departmentId = explode(',',$departmentId);
		$tables = array('tbl_department');
        $this->db->where_in('department_id',$departmentId);
        $this->db->delete($tables);

        $ids = is_array($departmentId) ? implode(',',$departmentId) : $departmentId;
		$response = array();
        if ($this->db->affected_rows()) {
            $response['success'] = "true";
        }
	
		return $response;
	}

	public function getDepartmentData($departmentId)
	{
		$this->db->select('td.department_id,td.dept_name,td.description');
		$this->db->from('tbl_department as td');
		$this->db->where('td.department_id', $departmentId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}


	function getDepartmentDD($data)
	{
		$this->db->select('td.department_id as id,td.dept_name as text');
		$this->db->from('tbl_department as td');
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("td.dept_name", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}

	public function getExistingDepartment($excludeId = '')
	{
		$this->db->select('dept_name');
		$this->db->from('tbl_department');
		if($excludeId != ''){
			$this->db->where('department_id != ',$excludeId);
		}
		$query = $this->db->get();
		$queryData = $query->result_array();
		return $queryData;

	}

	public function makeQuery()
	{
		$orderColumn = array("td.dept_name,td.description",null);
		$this->db->select("td.department_id,td.dept_name,td.description");
		$this->db->from("tbl_department as td");
		if (isset($_POST['search']['value'])) {
			$this->db->like('td.dept_name', $_POST['search']['value']);
		}
		if (isset($_POST['order'])) {
			$this->db->order_by($orderColumn[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		}else{
			$this->db->order_by('td.dept_name','asc');
		}
	}

	public function getDepartmentListing()
	{
		$this->makeQuery();
		if($_POST["length"] != -1){
			$this->db->limit($_POST["length"], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();


	}

	public function getFilteredData()
	{
		$this->makeQuery();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function getAllData()
	{
		$this->db->select("*");
		$this->db->from("tbl_department");
		return $this->db->count_all_results();
	}
}

