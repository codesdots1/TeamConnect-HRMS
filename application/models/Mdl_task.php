<?php


class Mdl_task extends DT_CI_Model
{

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
	}

	public function deleteRecord($taskId)
	{
		$tables = array('tbl_task');
		$this->db->where_in('task_id',$taskId);
		$this->db->delete($tables);
		$ids = is_array($taskId) ? implode(',',$taskId) : $taskId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getTaskData($taskId)
	{
		$this->db->select("tt.task_id,tt.task_name,tp.project_id,tp.project_name,tt.description,tt.is_active");
		$this->db->from('tbl_task as tt');
		$this->db->join('tbl_project as tp','tp.project_id = tt.project_id','left');
		$this->db->where('tt.task_id', $taskId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	public function getTaskInfoData($taskId)
	{
		$this->db->select("tt.task_id,tt.task_name,tp.project_id,tp.project_name,tt.description,tt.is_active");
		$this->db->from('tbl_task as tt');
		$this->db->join('tbl_project as tp','tp.project_id = tt.project_id','left');
		$this->db->where('tt.task_id', $taskId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}

	function getTaskDD($data)
	{
		if($data['project_id'] == ''){
			$result['result'] = array ( array ( 'id' => 0 , 'text' => 'First Select Project...' ));
		}else{
			$this->db->select('tt.task_id as id,tt.task_name as text');
			$this->db->from('tbl_task as tt');
			$this->db->where_in("project_id", $data['project_id']);
			if (isset($data['filter_param']) && $data['filter_param'] != '') {
				$this->db->like("tt.task_name", $data['filter_param'], 'both');
			}
			$query = $this->db->get();
			$result['result'] = $query->result_array();
			
			if (isset($data['all_task']) && $data['all_task'] == 'true') {
				$arr = array( 
				'id' => 'all',
				'text' => 'All Task'
				);
				array_unshift($result['result'] ,$arr);
			}
			
		}
		return json_encode($result);
	}

}
