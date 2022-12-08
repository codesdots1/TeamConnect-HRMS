<?php


class Mdl_work_week extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}


	public function deleteRecord($workWeekId = '')
	{
		$tables = array('tbl_work_weeks');
		$this->db->where_in('work_week_id', $workWeekId);
		$this->db->delete($tables);
		$ids = is_array($workWeekId) ? implode(',', $workWeekId) : $workWeekId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = true;
			logActivity('Work Week Deleted [GenderID: ' . $ids . ']', $this->data['userId'], 'WorkWeek');
		} 		
		return $response;
	}

	public function getWorkWeekData($workWeekId)
	{
		$this->db->select('tww.work_week_id,tww.title,tww.days_per_week,tww.days_name');
		$this->db->from('tbl_work_weeks as tww');
		$this->db->where('tww.work_week_id', $workWeekId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}


	function getWorkWeekDD($data)
	{	
		$this->db->select("tww.work_week_id as id,CONCAT(tww.title,' | ',tww.days_per_week) as text");
		$this->db->from('tbl_work_weeks as tww');
		if (isset($data['filter_param']) && $data['filter_param'] != '') {
			$this->db->like("tww.days_per_week", $data['filter_param'], 'both');
		}
		$query = $this->db->get();
		$result['result'] = $query->result_array();
		return json_encode($result);
	}

	public function makeQuery()
	{
		$order_column = array("tww.title,tww.days_per_week,tww.days_name",null);
		$this->db->select("tww.work_week_id,tww.title,tww.days_per_week,tww.days_name");
		$this->db->from("tbl_work_weeks as tww");
		if (isset($_POST['search']['value'])) {
			$this->db->like('tww.days_per_week', $_POST['search']['value']);
			//$this->db->or_like('c.description', $_POST['search']['value']);
		}
		if (isset($_POST['order'])) {
			$this->db->order_by($order_column[$_POST['order']['0']['column']],$_POST['order']['0']['dir']);
		}else{
			$this->db->order_by('tww.days_per_week','asc');
		}
	}

	public function getWorkWeekListing()
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
		$this->db->from("tbl_work_weeks");
		return $this->db->count_all_results();
	}
}
