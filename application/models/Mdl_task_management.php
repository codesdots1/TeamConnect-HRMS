<?php


class Mdl_task_management extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($taskManageId)
	{
		$tables = array('tbl_task_management');
		$this->db->where_in('task_manage_id',$taskManageId);
		$this->db->delete($tables);
		$ids = is_array($taskManageId) ? implode(',',$taskManageId) : $taskManageId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getTaskManagementData($taskManageId)
	{	
		$this->db->select("ttm.task_manage_id,ttm.emp_id,concat(te.first_name,' ',te.last_name) as emp_name,
		concat(te.first_name,' ',te.last_name,' | ',te.email ,' | ',tr.role ) as team_head,
		ttm.task_id_listing,ttm.project_id,tp.project_name");
		$this->db->from('tbl_employee as te');
		$this->db->join('tbl_task_management as ttm','ttm.emp_id = te.emp_id');
		$this->db->join('tbl_project as tp','tp.project_id = ttm.project_id');
		$this->db->join('tbl_role as tr','tr.role_id = te.role_id');
		$this->db->where('ttm.task_manage_id', $taskManageId);
		$query = $this->db->get();
		$queryData = $query->row_array(); 
		
		$ids = explode(",",$queryData['task_id_listing']);
		$tasks = array();
		
		foreach($ids as $id){
			$taskData = $this->Mdl_task->getTaskData($id);
			$name = ucwords($taskData['task_name']);
			$tasks[$id] = $name;
		}

		$queryData['tasks'] =   $tasks;
		return $queryData;
	}
	/*public function getTeamMember($teamHeadId)
	{	
		$this->db->select("tt.team_id,tt.emp_id as team_head_id,tt.team_name,concat(te.first_name,' ',te.last_name,' | ',te.email ,' | ',tr.role ) as team_head,
		tt.description,tt.emp_id_listing");
		$this->db->from('tbl_employee as te');
		$this->db->join('tbl_team as tt','tt.emp_id = te.emp_id');
		$this->db->join('tbl_role as tr','tr.role_id = te.role_id');
		$this->db->where('tt.emp_id', $teamHeadId);
		$query = $this->db->get();
		$queryData = $query->row_array(); 
		return $queryData;
	}*/
}
