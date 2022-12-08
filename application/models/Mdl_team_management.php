<?php


class Mdl_team_management extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($teamId)
	{
		$tables = array('tbl_team');
		$this->db->where_in('team_id',$teamId);
		$this->db->delete($tables);
		$ids = is_array($teamId) ? implode(',',$teamId) : $teamId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getTeamManagementData($teamId)
	{	
		$this->db->select("tt.team_id,tt.emp_id as team_head_id,tt.team_name,
		concat(te.first_name,' ',te.last_name,' | ',te.email ,' | ',tr.role ) as team_head,tt.description,tt.emp_id_listing,tt.project_id_listing");
		$this->db->from('tbl_employee as te');
		$this->db->join('tbl_team as tt','tt.emp_id = te.emp_id');
		$this->db->join('tbl_role as tr','tr.role_id = te.role_id');
		$this->db->where('tt.team_id', $teamId);
		$query = $this->db->get();
		$queryData = $query->row_array();

		$ids = explode(",",$queryData['emp_id_listing']);
		$team_members = array();
		
		foreach($ids as $id){
			$employeeData = $this->Mdl_employee->getEmployeeEmail($id);
			$name = ucwords($employeeData['emp_name']).' | '.$employeeData['email'];
			$team_members[$id] = $name;
		}

		$projectIds = explode(",",$queryData['project_id_listing']);
		$projects = array();
		foreach($projectIds as $id){
			$projectData = $this->Mdl_project->getProjectData($id);
			$name = ucwords($projectData['project_name']);
			$projects[$id] = $name;
		}

		$queryData['team_members'] = $team_members;
		$queryData['projects']     = $projects;
		return $queryData;
	}
	public function getTeamMember($teamHeadId)
	{
		$this->db->select("tt.team_id,tt.emp_id as team_head_id,tt.team_name,
		concat(te.first_name,' ',te.last_name,' | ',te.email ,' | ',tr.role ) as team_head,tt.description,tt.emp_id_listing");
		$this->db->from('tbl_employee as te');
		$this->db->join('tbl_team as tt','tt.emp_id = te.emp_id');
		$this->db->join('tbl_role as tr','tr.role_id = te.role_id');
		$this->db->where('tt.emp_id', $teamHeadId);
		$query = $this->db->get();
		$queryData = $query->row_array();
		return $queryData;
	}
}
