<?php


class Mdl_project_management extends DT_CI_Model
{
	public function __construct()
	{
		parent::__construct();
		// Your own constructor code

	}

	public function deleteRecord($projectManageId)
	{
		$tables = array('tbl_project_management');
		$this->db->where_in('project_manage_id',$projectManageId);
		$this->db->delete($tables);
		$ids = is_array($projectManageId) ? implode(',',$projectManageId) : $projectManageId;
		$response = array();
		if ($this->db->affected_rows()) {
			$response['success'] = "true";
		}

		return $response;
	}

	public function getProjectManagementData($projectManageId)
	{	
		$this->db->select("tpm.project_manage_id,tpm.emp_id as team_head_id,tpm.team_name,
			concat(te.first_name,' ',te.last_name,' | ',te.email ,' | ',tr.role ) as team_head,tpm.description,tpm.emp_id_listing,
			tpm.project_id,tp.project_name");
		$this->db->from('tbl_employee as te');
		$this->db->join('tbl_project_management as tpm','tpm.emp_id = te.emp_id');
		$this->db->join('tbl_role as tr','tr.role_id = te.role_id');
		$this->db->join('tbl_project as tp','tp.project_id = tpm.project_id');
		$this->db->where('tpm.project_manage_id', $projectManageId);
		$query = $this->db->get();
		$queryData = $query->row_array(); 
		
		$ids = explode(",",$queryData['emp_id_listing']);
		$team_members = array();
		
		foreach($ids as $id){
			$employeeData = $this->Mdl_employee->getEmployeeEmail($id);
			$name = ucwords($employeeData['emp_name']).' | '.$employeeData['email'];
			$team_members[$id] = $name;
		}

		$queryData['team_members'] =   $team_members;
		return $queryData;
	}
}
