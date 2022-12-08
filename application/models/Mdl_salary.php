<?php


class Mdl_salary extends DT_CI_Model
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}


	public function makeQuery()
	{
		$orderColumn = array("ts.amount,ts.esic,ts.pf",null);
		$this->db->select("ts.salary_id,ts.amount,ts.esic,ts.pf");
		$this->db->from("tbl_salary as ts");
		if (isset($_POST['search']['value'])) {
			$this->db->like('ts.amount', $_POST['search']['value']);
		}
		if (isset($_POST['order'])) {
			$this->db->order_by($orderColumn[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		}else{
			$this->db->order_by('ts.amount','asc');
		}
	}

	public function getSalaryListing()
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
		$this->db->from("tbl_salary");
		return $this->db->count_all_results();
	}

	public function deleteRecord($salaryId = '')
	{
		$salaryId = explode(',',$salaryId);
		$tables = array('tbl_salary');
        $this->db->where_in('salary_id',$salaryId);
        $this->db->delete($tables);

        $ids = is_array($salaryId) ? implode($salaryId) : $salaryId;
		$response = array();
        if ($this->db->affected_rows()) {
            $response['success'] = "true";
        }else{
			$response['success'] = "false";
		}
	
		return $response;

	}

	public function getTotalSalary(){
	    $this->db->select("SUM(amount) as salary");
		$this->db->from('tbl_salary');
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getSalaryData($salaryId)
	{
		$this->db->select('ts.salary_id,ts.amount,ts.esic,ts.pf');
		$this->db->from('tbl_salary as ts');
		$this->db->where('ts.salary_id', $salaryId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}


	function getSalaryDD($data){
		$this->db->select('ts.salary_id as id,ts.amount as text');
		$this->db->from('tbl_salary as ts');
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("ts.amount", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}
}


